<?php
class Periode extends Model
{
    public function enregistrementAnne()
    {
        $this->e(extract($_POST));

        // Vérifier s'il existe une période "inachevée"
        $periode_en_cours = $this->FetchAllSelectWhere(
            '*',
            'periode',
            'status = :status',
            [':status' => 'inachevé']
        );

        if (!empty($periode_en_cours)) {
            $this->set_flash('Impossible d\'ajouter une nouvelle période. Une période en cours existe déjà.', 'danger');
            return;
        }

        // Vérifier si la date de début est passée
        $date_actuelle = date('Y-m-d');
        if ($date_debut < $date_actuelle) {
            $this->set_flash('Impossible d\'ajouter une période avec une date passée.', 'danger');
            return;
        }

        // Vérifier si les dates sont déjà utilisées
        $periode_existe = $this->FetchAllSelectWhere(
            '*',
            'periode',
            'date_debut = :date_debut OR date_fin = :date_fin',
            [':date_debut' => $date_debut, ':date_fin' => $date_fin]
        );

        if (!empty($periode_existe)) {
            $this->set_flash('Une période avec ces dates existe déjà.', 'danger');
            return;
        }

        // Insérer la nouvelle période
        $insertion = $this->insertion_update_simples(
            'INSERT INTO periode (date_debut, date_fin, status) VALUES (:date_debut, :date_fin, :status)',
            [
                ':date_debut' => $date_debut,
                ':date_fin' => $date_fin,
                ':status' => 'inachevé'
            ]
        );

        if ($insertion) {
            $this->set_flash('Période ajoutée avec succès.', 'primary');
        } else {
            $this->set_flash('Erreur lors de l\'ajout de la période.', 'danger');
        }
    }

    public function verifierEtCreerPeriode()
    {
        // Vérifier s'il existe une période "inachevée"
        $periode_en_cours = $this->FetchAllSelectWhere(
            '*',
            'periode',
            'status = :status',
            [':status' => 'inachevé']
        );
        if (!empty($periode_en_cours)) {
            $periode = $periode_en_cours[0];
            $date_fin = $periode->date_fin;
            $date_actuelle = date('Y-m-d');

            // Vérifier si la période actuelle est terminée
            if ($date_actuelle >= $date_fin) {
                // Mettre à jour le statut de la période actuelle
                $this->insertion_update_simples(
                    'UPDATE periode SET status = :status WHERE id_periode = :id_periode',
                    [':status' => 'achevé', ':id_periode' => $periode->id_periode]
                );

                // Créer une nouvelle période à partir de la fin de l'ancienne
                $nouvelle_date_debut = date('Y-m-d', strtotime('+1 day', strtotime($date_fin)));
                $nouvelle_date_fin = date('Y-m-d', strtotime('+6 months', strtotime($nouvelle_date_debut)));

                if ($this->creerPeriode($nouvelle_date_debut, $nouvelle_date_fin)) {
                    $this->set_flash('Nouvelle période créée automatiquement.', 'primary');
                } else {
                    $this->set_flash('Erreur lors de la création de la nouvelle période.', 'danger');
                }
            }
        } else {
            // Aucune période trouvée : créer une nouvelle période basée sur la date actuelle
            $nouvelle_date_debut = date('Y-m-d');
            $nouvelle_date_fin = date('Y-m-d', strtotime('+6 months', strtotime($nouvelle_date_debut)));

            if ($this->creerPeriode($nouvelle_date_debut, $nouvelle_date_fin)) {
                $this->set_flash('Première période créée automatiquement.', 'primary');
            } else {
                $this->set_flash('Erreur lors de la création de la première période.', 'danger');
            }
        }
    }

    private function creerPeriode($date_debut, $date_fin)
    {
        return $this->insertion_update_simples(
            'INSERT INTO periode (date_debut, date_fin, status) VALUES (:date_debut, :date_fin, :status)',
            [
                ':date_debut' => $date_debut,
                ':date_fin' => $date_fin,
                ':status' => 'inachevé'
            ]
        );
    }
    public function modification($data)
    {
        $sql = 'UPDATE periode SET date_debut = :date_debut,date_fin = :date_fin  WHERE id_periode = :id_periode';

        $params = [
            
            ':date_debut' => $data['date_debut'],
            ':date_fin' => $data['date_fin'],
            ':id_periode' => $data['id_periode']
        ];
        // Exécution de la requête pour mettre à jour la matière
        $modifier = $this->insertion_update_simples($sql, $params);

        if ($modifier) {
            $this->set_flash("L'peride a été modifiée avec succès", 'primary');
            $this->redirect("Periodes/Liste");
        }
    }
}
