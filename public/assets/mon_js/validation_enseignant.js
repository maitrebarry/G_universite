document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".form");

  if (form) {
    const fieldIds = [
      "nom",
      "prenom",
      "date_naissance",
      "email",
      "telephone",
      "diplome",
      "statut",
      "cv",
    ];

    const fields = {};

    // Créer un objet 'fields' à partir des champs existants dans le formulaire
    fieldIds.forEach(function (id) {
      const element = document.getElementById(id);
      if (element) {
        fields[id] = element;
      }
    });

    form.addEventListener("submit", function (event) {
      let hasError = false;

      // Réinitialiser les styles d'erreur et les messages précédents
      Object.keys(fields).forEach(function (key) {
        const field = fields[key];
        if (field) {
          field.style.borderColor = "";
          const existingError = document.getElementById(`${key}_error`);
          if (existingError) {
            existingError.remove();
          }
        }
      });

      // Vérifier si les champs sont vides et afficher les erreurs
      Object.keys(fields).forEach(function (key) {
        const field = fields[key];
        if (field) {
          let isValid = true;

          if (field.type === "file" && !field.files.length) {
            isValid = false;
          } else if (field.value.trim() === "") {
            isValid = false;
          }

          if (!isValid) {
            // Ajouter les styles d'erreur
            field.style.borderColor = "#dc3545";

            // Créer et afficher un message d'erreur
            const errorMessage = document.createElement("div");
            errorMessage.id = `${key}_error`;
            errorMessage.style.color = "#dc3545";
            errorMessage.textContent = `Le champ ${formatLabel(
              key
            )} est obligatoire`;
            field.parentElement.appendChild(errorMessage);

            hasError = true;
          }
        }
      });

      // Empêcher l'envoi du formulaire si des erreurs sont présentes
      if (hasError) {
        event.preventDefault();
        event.stopPropagation();
      }
    });

    // Ajouter un écouteur d'événements 'input' pour réinitialiser les erreurs
    Object.keys(fields).forEach(function (key) {
      const field = fields[key];
      if (field) {
        field.addEventListener("input", function () {
          field.style.borderColor = "";
          const existingError = document.getElementById(`${key}_error`);
          if (existingError) {
            existingError.remove();
          }
        });
      }
    });
  }

  // Fonction pour formater les labels des champs
  function formatLabel(key) {
    switch (key) {
      case "nom":
        return "Nom";
      case "prenom":
        return "Prénom";
      case "date_naissance":
        return "Date de naissance";
      case "email":
        return "Email";
      case "telephone":
        return "Téléphone";
      case "diplome":
        return "Diplôme";
      case "statut":
        return "Statut";
      case "cv":
        return "CV";
      default:
        return key.replace(/_/g, " ");
    }
  }
});
