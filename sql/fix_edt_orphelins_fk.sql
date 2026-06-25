-- =====================================================================
-- Intégrité référentielle de l'emploi du temps (EDT)
-- ---------------------------------------------------------------------
-- Contexte : les tables enfants `enseignant_edt`, `horaire` et `tache`
-- n'ont AUCUNE clé étrangère. Conséquence : supprimer un `edt` (ou un
-- `horaire`) laisse des lignes orphelines.
--
-- État constaté le 2026-06-24 (base db_universite, MariaDB 10.4, InnoDB) :
--   * enseignant_edt orphelines (id_edt absent de edt) : 4  -> id 9,10,4,11 (EDT 5/6 supprimés)
--   * horaire orphelines                                : 0
--   * tache orphelines (id_horaire absent de horaire)   : 1  -> id_tache 25 (horaire 5)
--   * enseignant_edt -> enseignants invalides           : 5  -> dont 2 (id 5 et 12)
--       rattachées à l'EDT #1 VIVANT mais pointant vers les
--       enseignants 18 et 123 qui n'existent plus.
--   * enseignant_edt -> salle invalides                 : 0
--   * tache -> jour invalides                           : 0
--   (Seul l'EDT id_edt=1 existe encore.)
--
-- Ce script : (0) sauvegarde, (1) supprime les orphelins, (2) ajoute les
-- clés étrangères manquantes — CASCADE pour les liens enfant->EDT et
-- tache->horaire ; RESTRICT pour enseignants / salle / jour.
--
-- ⚠️ DÉCISIONS PAR DÉFAUT (modifiables ci-dessous avant exécution) :
--   [D1] Lignes 5 & 12 (EDT #1 -> enseignants 18/123 disparus) : SUPPRIMÉES.
--        Alternatives en commentaire : (b) mettre id_enseignant à NULL,
--        (c) reporter la FK vers enseignants.
--   [D2] ON DELETE des FK enseignants / salle : RESTRICT.
--        Alternative CASCADE en commentaire.
--
-- ⚠️ Sauvegarde externe déjà réalisée : sql/backup_edt_2026-06-24.sql
-- À exécuter UNE FOIS (base db_universite), p.ex. :
--   C:\xampp\mysql\bin\mysql.exe -u root db_universite < sql/fix_edt_orphelins_fk.sql
-- =====================================================================

-- ---------------------------------------------------------------------
-- 0) Sauvegarde inline (convention projet : tables *_sauvegarde)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS enseignant_edt_sauvegarde AS SELECT * FROM enseignant_edt;
CREATE TABLE IF NOT EXISTS horaire_sauvegarde         AS SELECT * FROM horaire;
CREATE TABLE IF NOT EXISTS tache_sauvegarde           AS SELECT * FROM tache;

-- ---------------------------------------------------------------------
-- 1) Suppression des orphelins (ordre enfant -> parent)
-- ---------------------------------------------------------------------
-- 1a. tache sans horaire parent  (id_tache 25)
DELETE t FROM tache t
LEFT JOIN horaire h ON t.id_horaire = h.id_horaire
WHERE h.id_horaire IS NULL;

-- 1b. enseignant_edt sans edt parent  (id 9,10,4,11)
DELETE ee FROM enseignant_edt ee
LEFT JOIN edt e ON ee.id_edt = e.id_edt
WHERE e.id_edt IS NULL;

-- 1c. horaire sans edt parent  (0 ligne actuellement — sécurité / ré-exécution)
DELETE h FROM horaire h
LEFT JOIN edt e ON h.id_edt = e.id_edt
WHERE e.id_edt IS NULL;

-- ---------------------------------------------------------------------
-- [D1] enseignant_edt pointant vers un enseignant inexistant.
--      Après 1b, il ne reste que id 5 et 12 (rattachées à l'EDT #1).
--      >>> DÉFAUT : suppression. <<<
-- ---------------------------------------------------------------------
DELETE ee FROM enseignant_edt ee
LEFT JOIN enseignants en ON ee.id_enseignant = en.enseignant_id
WHERE en.enseignant_id IS NULL;

-- --- ALTERNATIVE D1-b : conserver les créneaux, sans titulaire -------
--   (Commenter le DELETE [D1] ci-dessus, décommenter ces 2 lignes, ET
--    passer la FK fk_ee_enseignant en ON DELETE SET NULL plus bas.)
-- ALTER TABLE enseignant_edt MODIFY id_enseignant int(11) NULL;
-- UPDATE enseignant_edt ee
--   LEFT JOIN enseignants en ON ee.id_enseignant = en.enseignant_id
--   SET ee.id_enseignant = NULL
--   WHERE en.enseignant_id IS NULL;
--
-- --- ALTERNATIVE D1-c : reporter la FK vers enseignants --------------
--   (Commenter le DELETE [D1] ci-dessus ET la contrainte fk_ee_enseignant
--    plus bas. Les lignes 5 et 12 restent en l'état, sans FK enseignants.)

-- ---------------------------------------------------------------------
-- 2) Ajout des clés étrangères
-- ---------------------------------------------------------------------
-- 2a. Liens enfant -> EDT et tache -> horaire : ON DELETE CASCADE (cœur du correctif)
ALTER TABLE enseignant_edt
  ADD CONSTRAINT fk_ee_edt FOREIGN KEY (id_edt) REFERENCES edt (id_edt)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE horaire
  ADD CONSTRAINT fk_horaire_edt FOREIGN KEY (id_edt) REFERENCES edt (id_edt)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE tache
  ADD CONSTRAINT fk_tache_horaire FOREIGN KEY (id_horaire) REFERENCES horaire (id_horaire)
  ON DELETE CASCADE ON UPDATE CASCADE;

-- 2b. Liens vers les données de référence : RESTRICT
ALTER TABLE tache
  ADD CONSTRAINT fk_tache_jour FOREIGN KEY (id_jour) REFERENCES jour (id_jour)
  ON DELETE RESTRICT ON UPDATE CASCADE;

-- [D2] enseignants / salle : >>> DÉFAUT RESTRICT <<<
--   (Pour l'alternative CASCADE : remplacer "ON DELETE RESTRICT" par
--    "ON DELETE CASCADE" dans les deux contraintes ci-dessous.
--    Pour l'alternative D1-b SET NULL : mettre "ON DELETE SET NULL" sur fk_ee_enseignant.)
ALTER TABLE enseignant_edt
  ADD CONSTRAINT fk_ee_enseignant FOREIGN KEY (id_enseignant) REFERENCES enseignants (enseignant_id)
  ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE enseignant_edt
  ADD CONSTRAINT fk_ee_salle FOREIGN KEY (id_salle) REFERENCES salle (id_salle)
  ON DELETE RESTRICT ON UPDATE CASCADE;

-- =====================================================================
-- 3) VÉRIFICATIONS (à lancer manuellement après exécution)
-- =====================================================================
-- 3a. Les 6 FK sont-elles posées ?
-- SELECT TABLE_NAME, CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
-- FROM information_schema.KEY_COLUMN_USAGE
-- WHERE TABLE_SCHEMA='db_universite' AND REFERENCED_TABLE_NAME IS NOT NULL
--   AND TABLE_NAME IN ('enseignant_edt','horaire','tache')
-- ORDER BY TABLE_NAME, CONSTRAINT_NAME;
--
-- 3b. Reste-t-il des orphelins ? (doit renvoyer 0, 0, 0)
-- SELECT
--   (SELECT COUNT(*) FROM enseignant_edt ee LEFT JOIN edt e ON ee.id_edt=e.id_edt WHERE e.id_edt IS NULL)            AS ee_orph,
--   (SELECT COUNT(*) FROM horaire h        LEFT JOIN edt e ON h.id_edt=e.id_edt  WHERE e.id_edt IS NULL)            AS hor_orph,
--   (SELECT COUNT(*) FROM tache t          LEFT JOIN horaire h ON t.id_horaire=h.id_horaire WHERE h.id_horaire IS NULL) AS tache_orph;
--
-- 3c. Test de cascade (optionnel, sur un EDT jetable) :
--   INSERT INTO edt (...) VALUES (...);                         -- créer un EDT test
--   INSERT INTO horaire (heure_debut,heure_fin,id_edt) VALUES ('08:00','10:00',@e:=LAST_INSERT_ID()+0);
--   -- ... insérer tache + enseignant_edt liés ...
--   DELETE FROM edt WHERE id_edt = <id_test>;                   -- doit vider horaire/enseignant_edt liés
--   -- la suppression du horaire doit aussi vider ses tache (cascade tache->horaire).
--
-- Restauration si besoin : sql/backup_edt_2026-06-24.sql
--   (ou : INSERT ... SELECT depuis enseignant_edt_sauvegarde / horaire_sauvegarde / tache_sauvegarde)
-- =====================================================================
