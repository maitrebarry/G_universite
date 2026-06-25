-- =====================================================================
-- Normalisation de etudiant.id_statut vers des codes canoniques.
-- Avant : REG, REGULIER, CL, CANDIDAT LIBRE, FC, Pro. Collect, Pro. Colect,
--         Pro. Etat, PROFPUBLIC, Pro. Priv, Pro. Prov, PROPRIVE …
-- Après : REGULIER | CANDIDAT_LIBRE | FORMATION_CONTINUE | PRO_PUBLIC | PRO_PRIVE
-- ⚠️ Sauvegarde automatique dans etudiant_sauvegarde_avant_statut.
-- =====================================================================

CREATE TABLE IF NOT EXISTS etudiant_sauvegarde_avant_statut AS SELECT * FROM etudiant;

UPDATE etudiant SET id_statut = 'REGULIER'
  WHERE LOWER(TRIM(id_statut)) IN ('reg', 'regulier', 'regle');

UPDATE etudiant SET id_statut = 'CANDIDAT_LIBRE'
  WHERE LOWER(TRIM(id_statut)) IN ('cl', 'candidat libre');

UPDATE etudiant SET id_statut = 'FORMATION_CONTINUE'
  WHERE LOWER(TRIM(id_statut)) = 'fc';

-- Privé (avant Public pour éviter tout recouvrement)
UPDATE etudiant SET id_statut = 'PRO_PRIVE'
  WHERE (LOWER(id_statut) LIKE '%priv%' OR LOWER(id_statut) LIKE '%prov%' OR LOWER(id_statut) = 'proprive')
    AND id_statut NOT IN ('REGULIER', 'CANDIDAT_LIBRE', 'FORMATION_CONTINUE');

-- Public / collectivité / état
UPDATE etudiant SET id_statut = 'PRO_PUBLIC'
  WHERE (LOWER(id_statut) LIKE '%pub%' OR LOWER(id_statut) LIKE '%collect%'
         OR LOWER(id_statut) LIKE '%colect%' OR LOWER(id_statut) LIKE '%etat%')
    AND id_statut NOT IN ('REGULIER', 'CANDIDAT_LIBRE', 'FORMATION_CONTINUE', 'PRO_PRIVE');
