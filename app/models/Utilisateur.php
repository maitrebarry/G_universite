<?php
class Utilisateur extends Model
{
  public $errors = [];
  public function upload_cv($file)
  {
      $upload_dir = 'C:/xampp/htdocs/G_universite/public/signature/'; // Dossier de destination
      $taillemax = 2067152; // 2 Mo
      $extensions_valides = ['gif', 'png', 'jpg', 'jpeg'];
      $mime_types_valides = ['image/gif', 'image/png', 'image/jpeg']; // Vérification par MIME type
  
      // Vérifiez si le fichier est passé
      if (!isset($file['name']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
          $this->errors[] = "Le fichier image est obligatoire.";
          return false; // Retourne false pour indiquer une erreur
      }
  
      // Vérifiez les erreurs spécifiques d'upload
      if ($file['error'] !== UPLOAD_ERR_OK) {
          switch ($file['error']) {
              case UPLOAD_ERR_INI_SIZE:
              case UPLOAD_ERR_FORM_SIZE:
                  $this->errors[] = "Le fichier dépasse la taille maximale autorisée.";
                  break;
              case UPLOAD_ERR_PARTIAL:
                  $this->errors[] = "Le fichier n'a été que partiellement téléchargé.";
                  break;
              default:
                  $this->errors[] = "Erreur inconnue lors de l'upload.";
                  break;
          }
          return false; // Retourne false en cas d'erreur
      }
  
      $file_name = basename($file['name']);
      $file_tmp = $file['tmp_name'];
      $file_size = $file['size'];
      $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
      $file_mime = mime_content_type($file_tmp);
  
      // Vérification de la taille
      if ($file_size > $taillemax) {
          $this->errors[] = "Taille du fichier trop grande. Taille maximale autorisée : 2 Mo.";
          return false; // Retourne false en cas d'erreur
      }
  
      // Vérification de l'extension et du type MIME
      if (!in_array($file_extension, $extensions_valides) || !in_array($file_mime, $mime_types_valides)) {
          $this->errors[] = "Extension ou type de fichier non valide. Extensions autorisées : .gif, .png, .jpg.";
          return false; // Retourne false en cas d'erreur
      }
  
      // Renommage unique du fichier
      $new_file_name = uniqid('profile_', true) . '.' . $file_extension;
      $destination = $upload_dir . $new_file_name;
  
      // Déplacement du fichier
      if (move_uploaded_file($file_tmp, $destination)) {
          return '/signature/' . $new_file_name; // Chemin relatif enregistré dans la base
      } else {
          $this->errors[] = "Erreur lors du déplacement du fichier.";
          return false; // Retourne false en cas d'erreur
      }
  }

  // function d'enregistrement
  public function save_utilisateur()
  {
      $this->e(extract($_POST)); // Extraction des données POST
      $hashPwd = password_hash($mot_passe, PASSWORD_DEFAULT); // Hash du mot de passe
  
      // Vérification et téléchargement de l'image
      $signature_path = null;
      if (isset($_FILES['signature']) && $_FILES['signature']['error'] === UPLOAD_ERR_OK) {
          $signature_path = $this->upload_cv($_FILES['signature']);
          if ($signature_path === false) {
              // Gérer l'erreur d'upload
              $this->set_flash("Erreur lors du téléchargement de la signature : " . implode(", ", $this->errors), 'danger');
              $this->redirect("Utilisateurs/liste_utilisateur");
              return;
          }
      } else {
          $this->set_flash("La signature est obligatoire.", 'danger');
          $this->redirect("Utilisateurs/liste_utilisateur");
          return;
      }
  
      // Insertion des données dans la base
      $insert = $this->insertion_update_simples(
          "INSERT INTO utilisateur (nom_prenom, contact_utilisateur, email_utilisateurs, mot_passe, role, signature) 
           VALUES (:nom_prenom, :contact_utilisateur, :email_utilisateurs, :mot_passe, :role, :signature)",
          [
              ":nom_prenom" => $nom_prenom,
              ":contact_utilisateur" => $contact_utilisateur,
              ":email_utilisateurs" => $email_utilisateurs,
              ":mot_passe" => $hashPwd,
              ":role" => $role,
              ":signature" => $signature_path
          ]
      );
  
      if ($insert) {
          $this->set_flash("Utilisateur ajouté avec succès.", 'primary');
          $this->redirect("Utilisateurs/liste_utilisateur");
      } else {
          $this->set_flash("Erreur lors de l'insertion.", 'danger');
          $this->redirect("Utilisateurs/liste_utilisateur");
      }
  }
  
  public function edit_utilisateur($data)
  {
    $req = "UPDATE utilisateur 
             SET nom_prenom =:nom_prenom, 
                  contact_utilisateur=:contact_utilisateur,
                  email_utilisateurs=:email_utilisateurs,
                  mot_passe=:mot_passe,
                  role=:role
                  WHERE id_utilisateur=:id_utilisateur";

    $params = [
      ":nom_prenom" => $data['nom_prenom'],
      ":contact_utilisateur" => $data['contact_utilisateur'],
      ":email_utilisateurs" => $data['email_utilisateurs'],
      ":mot_passe" => $data['mot_passe'],
      ":role" => $data['role'],
      ':id_utilisateur' => $data['id_utilisateur'],
    ];

    $modification = $this->insertion_update_simples($req, $params);

    if ($modification == true) {
      $this->set_flash("modification faite avec succes", "sucess");
      $this->redirect("Utilisateurs/liste_utilisateur");
    }
  }
}
