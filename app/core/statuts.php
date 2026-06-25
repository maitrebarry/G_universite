<?php
/**
 * Référentiel UNIQUE des statuts étudiant.
 * Les données historiques sont incohérentes (REG / REGULIER / CL / CANDIDAT LIBRE /
 * FC / Pro. Collect / PROFPUBLIC / Pro. Priv / PROPRIVE …). Ce fichier définit les
 * codes canoniques + libellés, et des helpers pour normaliser/afficher.
 */

$GU_STATUTS = [
    'REGULIER'           => 'Régulier',
    'CANDIDAT_LIBRE'     => 'Candidat libre',
    'FORMATION_CONTINUE' => 'Formation continue',
    'PRO_PUBLIC'         => 'Professionnel public',
    'PRO_PRIVE'          => 'Professionnel privé',
];

/** Frais total selon le statut canonique. */
$GU_STATUT_FRAIS = [
    'REGULIER'           => 6000,
    'CANDIDAT_LIBRE'     => 81000,
    'FORMATION_CONTINUE' => 81000,
    'PRO_PUBLIC'         => 150000,
    'PRO_PRIVE'          => 200000,
];

/** Convertit une valeur brute (libre) en code canonique, ou null si inconnu. */
function gu_statut_normalize($raw)
{
    $s = strtolower(trim((string) $raw));
    $s = strtr($s, ['é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e', 'à' => 'a']);
    $s = preg_replace('/[^a-z]/', '', $s); // ne garder que les lettres (gère "Pro. Priv.", mojibake, etc.)
    if ($s === '') return null;

    if (in_array($s, ['reg', 'regulier', 'regle'], true)) return 'REGULIER';
    if (in_array($s, ['cl', 'candidatlibre'], true))       return 'CANDIDAT_LIBRE';
    if (in_array($s, ['fc', 'formationcontinue'], true))   return 'FORMATION_CONTINUE';
    if (strpos($s, 'priv') !== false || $s === 'proprive') return 'PRO_PRIVE';
    if (strpos($s, 'pub') !== false || strpos($s, 'collect') !== false
        || strpos($s, 'colect') !== false || strpos($s, 'etat') !== false) return 'PRO_PUBLIC';
    if (strpos($s, 'prof') === 0 || strpos($s, 'pro') === 0) return 'PRO_PUBLIC'; // "professionnel" générique
    return null;
}

/** Libellé lisible pour l'affichage (canonique si reconnu, sinon valeur brute). */
function gu_statut_label($raw)
{
    global $GU_STATUTS;
    $code = gu_statut_normalize($raw);
    if ($code && isset($GU_STATUTS[$code])) return $GU_STATUTS[$code];
    $r = trim((string) $raw);
    return $r !== '' ? $r : '—';
}

/** Liste canonique code => libellé (pour les <select>). */
function gu_statuts()
{
    global $GU_STATUTS;
    return $GU_STATUTS;
}

/** Clé de rapprochement nom+prénom (doublons), insensible à la casse et aux accents. */
function gu_person_key($nom, $prenom)
{
    $s = strtolower(trim((string) $nom) . ' ' . trim((string) $prenom));
    $s = strtr($s, ['à' => 'a', 'â' => 'a', 'ä' => 'a', 'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e', 'î' => 'i', 'ï' => 'i', 'ô' => 'o', 'ö' => 'o', 'ù' => 'u', 'û' => 'u', 'ü' => 'u', 'ç' => 'c']);
    return preg_replace('/[^a-z0-9]/', '', $s);
}

/** Frais selon une valeur de statut (brute ou canonique). */
function gu_statut_frais($raw)
{
    global $GU_STATUT_FRAIS;
    $code = gu_statut_normalize($raw);
    return $code && isset($GU_STATUT_FRAIS[$code]) ? $GU_STATUT_FRAIS[$code] : 150000;
}
