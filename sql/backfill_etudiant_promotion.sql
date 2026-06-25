-- =====================================================================
-- Rattrapage de cohérence : etudiant.id_promotion + etudiant.id_filiere
-- ---------------------------------------------------------------------
-- Contexte : les insertions (inscription + import) liaient l'étudiant à sa
-- promotion via la table etudiant_promotion, mais ne remplissaient PAS les
-- colonnes etudiant.id_promotion / etudiant.id_filiere, alors que plusieurs
-- écrans (tableaux de bord, listes filtrées) lisent ces colonnes.
--
-- Ce script renseigne ces colonnes pour les étudiants DÉJÀ en base, à partir
-- du lien etudiant_promotion -> promotion.
--
-- À exécuter UNE FOIS dans phpMyAdmin (base db_universite).
-- ⚠️ Faites une sauvegarde de la table `etudiant` avant.
-- Note : si un étudiant a plusieurs promotions, on prend la plus récente
-- (id_promotion le plus élevé).
-- =====================================================================

UPDATE etudiant e
JOIN (
    SELECT ep.id_etudiants, MAX(ep.id_promotion) AS id_promotion
    FROM etudiant_promotion ep
    GROUP BY ep.id_etudiants
) dern ON dern.id_etudiants = e.id_etudiant
JOIN promotion p ON p.id_promotion = dern.id_promotion
SET e.id_promotion = p.id_promotion,
    e.id_filiere   = p.id_filiere
WHERE e.id_promotion IS NULL OR e.id_promotion = 0
   OR e.id_filiere   IS NULL OR e.id_filiere   = 0;

-- Vérification : combien d'étudiants restent sans promotion après coup ?
-- SELECT COUNT(*) AS sans_promotion FROM etudiant WHERE id_promotion IS NULL OR id_promotion = 0;
