# -*- coding: utf-8 -*-
# import sys
# sys.path.append(r"C:\Users\DELL\AppData\Roaming\Python\Python313\site-packages")

# import json
# import mysql.connector
# import io

# # ✅ Forcer la sortie UTF-8
# sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

# # 🔹 Config MySQL
# config = {
#     "host": "localhost",
#     "user": "root",
#     "password": "",
#     "database": "db_universite"
# }

# def connect_db():
#     return mysql.connector.connect(**config)

# # ✅ Réponses intelligentes
# def handle_chat(question):
#     q = question.lower().strip()

#     # 🔹 Réponses générales
#     if "salut" in q or "bonjour" in q:
#         return "Bonjour 👋 ! Comment puis-je vous aider ?"
#     if "tu es là" in q:
#         return "Oui, je suis là pour vous aider ! 😊"
#     if "merci" in q:
#         return "Avec plaisir ! N'hésitez pas si vous avez d'autres questions."

#     # 🔹 Connexion DB
#     try:
#         cnx = connect_db()
#         cursor = cnx.cursor()

#         # ✅ Taux de réussite
#         if "taux de réussite" in q:
#             cursor.execute("""
#                 SELECT COUNT(DISTINCT e.id_etudiant),
#                        COUNT(DISTINCT CASE WHEN n.moyenne_module >= 10 THEN e.id_etudiant END)
#                 FROM etudiant e
#                 JOIN payement p ON p.idEtudt = e.id_etudiant
#                 LEFT JOIN note_etudiant n ON n.id_etudiant = e.id_etudiant
#                 WHERE p.montant_paye > 0
#             """)
#             total, admis = cursor.fetchone()
#             if total > 0:
#                 taux = round((admis / total) * 100, 2)
#                 return f"Le taux de réussite actuel est de {taux} %."
#             return "Aucun inscrit trouvé."

#         # ✅ Taux d'échec
#         if "taux d'échec" in q:
#             cursor.execute("""
#                 SELECT COUNT(DISTINCT e.id_etudiant),
#                        COUNT(DISTINCT CASE WHEN n.moyenne_module < 10 THEN e.id_etudiant END)
#                 FROM etudiant e
#                 JOIN payement p ON p.idEtudt = e.id_etudiant
#                 LEFT JOIN note_etudiant n ON n.id_etudiant = e.id_etudiant
#                 WHERE p.montant_paye > 0
#             """)
#             total, echec = cursor.fetchone()
#             if total > 0:
#                 taux = round((echec / total) * 100, 2)
#                 return f"Le taux d'échec actuel est de {taux} %."
#             return "Aucun inscrit trouvé."

#         # ✅ Nombre total d'étudiants
#         if "combien d'étudiants" in q or "nombre total" in q:
#             cursor.execute("SELECT COUNT(*) FROM etudiant")
#             total = cursor.fetchone()[0]
#             return f"Actuellement, il y a {total} étudiants inscrits."

#         # ✅ Taux d'inscription
#         if "taux d'inscription" in q:
#             cursor.execute("""
#                 SELECT (SELECT COUNT(*) FROM payement WHERE montant_paye > 0),
#                        (SELECT COUNT(*) FROM etudiant)
#             """)
#             inscrits, total = cursor.fetchone()
#             if total > 0:
#                 taux = round((inscrits / total) * 100, 2)
#                 return f"Le taux d'inscription est de {taux} %."
#             return "Impossible de calculer le taux d'inscription."

#         # ✅ Meilleur département
#         if "meilleur département" in q:
#             cursor.execute("""
#                 SELECT d.nom_departement,
#                        ROUND(IF(COUNT(DISTINCT e.id_etudiant) > 0,
#                        (COUNT(DISTINCT CASE WHEN n.moyenne_module >= 10 THEN e.id_etudiant END) / COUNT(DISTINCT e.id_etudiant))*100, 0), 2) AS taux
#                 FROM departement d
#                 LEFT JOIN filiere f ON f.id_departement = d.id_departement
#                 LEFT JOIN promotion pr ON pr.id_filiere = f.id_filiere
#                 LEFT JOIN etudiant e ON e.id_promotion = pr.id_promotion
#                 LEFT JOIN note_etudiant n ON n.id_etudiant = e.id_etudiant
#                 GROUP BY d.id_departement
#                 ORDER BY taux DESC LIMIT 1
#             """)
#             dep = cursor.fetchone()
#             return f"Le meilleur département est {dep[0]} avec {dep[1]} % de réussite."

#         # ✅ Pire département
#         if "pire département" in q or "moins bon département" in q:
#             cursor.execute("""
#                 SELECT d.nom_departement,
#                        ROUND(IF(COUNT(DISTINCT e.id_etudiant) > 0,
#                        (COUNT(DISTINCT CASE WHEN n.moyenne_module >= 10 THEN e.id_etudiant END) / COUNT(DISTINCT e.id_etudiant))*100, 0), 2) AS taux
#                 FROM departement d
#                 LEFT JOIN filiere f ON f.id_departement = d.id_departement
#                 LEFT JOIN promotion pr ON pr.id_filiere = f.id_filiere
#                 LEFT JOIN etudiant e ON e.id_promotion = pr.id_promotion
#                 LEFT JOIN note_etudiant n ON n.id_etudiant = e.id_etudiant
#                 GROUP BY d.id_departement
#                 ORDER BY taux ASC LIMIT 1
#             """)
#             dep = cursor.fetchone()
#             return f"Le département le moins performant est {dep[0]} avec {dep[1]} % de réussite."

#         # ✅ Liste des départements
#         if "liste des départements" in q or "tous les départements" in q:
#             cursor.execute("SELECT nom_departement FROM departement")
#             deps = [row[0] for row in cursor.fetchall()]
#             return "Voici la liste des départements : " + ", ".join(deps)

#         cursor.close()
#         cnx.close()

#     except Exception as e:
#         return f"Erreur DB : {str(e)}"

#     return ("Je ne comprends pas encore cette question, mais vous pouvez essayer : "
#             "« taux de réussite », « meilleur département », « nombre total d'étudiants ».")

# def main():
#     try:
#         raw_data = sys.stdin.read()
#         data = json.loads(raw_data)

#         question = data.get("question", "").strip()
#         if not question:
#             print(json.dumps({"response": "⚠️ Question vide."}, ensure_ascii=False))
#             return

#         answer = handle_chat(question)
#         print(json.dumps({"response": answer}, ensure_ascii=False))

#     except Exception as e:
#         print(json.dumps({"response": f"⚠️ Erreur Python : {str(e)}"}, ensure_ascii=False))

# if __name__ == "__main__":
#     main()
# -*- coding: utf-8 -*-


# -*- coding: utf-8 -*-
import sys
sys.path.append(r"C:\Users\DELL\AppData\Roaming\Python\Python313\site-packages")
import json
import mysql.connector
import io
import re
import random
from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize
from rapidfuzz import fuzz

# ✅ Forcer UTF-8
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

# 🔹 Config MySQL
config = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "db_universite"
}

def connect_db():
    return mysql.connector.connect(**config)

# ✅ Nettoyage & Tokenisation
def preprocess_question(question):
    question = question.lower()
    question = re.sub(r"[^\w\s]", " ", question)
    tokens = word_tokenize(question, language='french')
    stop_words = set(stopwords.words('french'))
    filtered_tokens = [w for w in tokens if w.isalpha() and w not in stop_words]
    return filtered_tokens

# ✅ Intents enrichis avec synonymes
INTENTS = {
    "salutation": [["salut"], ["bonjour"], ["bonsoir"]],
    "etat": [["tu", "vas"], ["ça", "va"], ["comment", "vas"]],
    "merci": [["merci"], ["thanks"], ["cimer"]],

    "taux_reussite": [["taux", "réussite"], ["succès"], ["réussir"]],
    "taux_echec": [["taux", "échec"], ["échec"]],
    "nombre_etudiants": [["nombre", "étudiants"], ["combien", "étudiants"], ["total", "étudiants"], ["nombre", "élèves"]],
    "nombre_inscrits": [["nombre", "inscrits"], ["combien", "inscrits"], ["total", "inscrits"], ["élèves", "inscrits"], ["étudiants", "inscrits"]],
    "nombre_non_inscrits": [["non inscrits"], ["pas inscrits"]],
    "taux_inscription": [["taux", "inscription"], ["pourcentage", "inscription"]],
    "meilleur_departement": [["meilleur", "département"], ["top", "département"]],
    "pire_departement": [["pire", "département"], ["moins", "bon", "département"]],
    "liste_departements": [["liste", "départements"], ["tous", "départements"], ["lesquels", "départements"]],
    "nombre_departements": [["combien", "départements"], ["nombre", "départements"]],
    "etudiants_par_departement": [["par", "département"], ["répartition", "départements"]],
    "nombre_filles": [["combien", "filles"], ["nombre", "filles"]],
    "nombre_garcons": [["combien", "garçons"], ["nombre", "garçons"]],
    "admis": [["combien", "moyenne"], ["admis"], ["réussite"]],
    "nombre_filiere": [["nombre", "filières"], ["combien", "filières"]],
    "liste_filieres": [["liste", "filières"], ["lesquelles", "filières"], ["toutes", "filières"]],
    "nombre_enseignants": [["nombre", "enseignants"], ["combien", "enseignants"], ["total", "enseignants"]],
    "enseignants_permanents": [["enseignants", "permanents"], ["combien", "permanents"]],
    "enseignants_non_permanents": [["enseignants", "non", "permanents"], ["enseignants", "vacataires"], ["combien", "vacataires"]]
}

# ✅ Contexte pour "lesquels / lesquelles"
last_question = None

# ✅ Matching avec RapidFuzz
def match_intent(tokens):
    best_intent = None
    best_score = 0
    joined_tokens = " ".join(tokens)

    for intent, patterns in INTENTS.items():
        for pattern in patterns:
            pattern_text = " ".join(pattern)
            score = fuzz.partial_ratio(joined_tokens, pattern_text)
            if score > best_score and score >= 70:
                best_score = score
                best_intent = intent
    return best_intent

# ✅ Réponses variées
def random_reply(options):
    return random.choice(options)

# ✅ Fonction principale
def handle_chat(question):
    global last_question
    tokens = preprocess_question(question)
    intent = match_intent(tokens)

    # ✅ Gestion contexte
    if intent is None and ("lesquels" in question or "les quelles" in question):
        if last_question == "nombre_departements":
            intent = "liste_departements"
        elif last_question == "nombre_filiere":
            intent = "liste_filieres"

    # ✅ Sauvegarde contexte
    last_question = intent

    if intent == "salutation":
        return random_reply(["Bonjour 👋 ! Comment puis-je vous aider ?", "Salut ! Que puis-je faire pour vous ?", "Hello 😊"])
    if intent == "etat":
        return random_reply(["Je vais très bien, merci ! Et vous ?", "En pleine forme 🚀, et vous ?", "Je vais bien, merci 😊"])
    if intent == "merci":
        return random_reply(["Avec plaisir !", "De rien 😎", "Toujours là pour vous aider !"])

    try:
        cnx = connect_db()
        cursor = cnx.cursor()

        # ✅ Stats générales
        cursor.execute("SELECT COUNT(*) FROM etudiant")
        total_etudiants = cursor.fetchone()[0]

        cursor.execute("""
            SELECT COUNT(DISTINCT e.id_etudiant)
            FROM etudiant e
            JOIN payement p ON p.idEtudt = e.id_etudiant
            WHERE p.montant_paye > 0 AND p.date IS NOT NULL
        """)
        total_inscrits = cursor.fetchone()[0]

        taux_inscription = round((total_inscrits / total_etudiants) * 100, 2) if total_etudiants > 0 else 0

        cursor.execute("""
            SELECT COUNT(DISTINCT n.id_etudiant)
            FROM note_etudiant n
            JOIN etudiant e ON e.id_etudiant = n.id_etudiant
            JOIN payement p ON p.idEtudt = e.id_etudiant
            WHERE n.moyenne_module >= 10 AND p.montant_paye > 0 AND p.date IS NOT NULL
        """)
        total_admis = cursor.fetchone()[0]

        taux_reussite = round((total_admis / total_inscrits) * 100, 2) if total_inscrits > 0 else 0
        taux_echec = round(100 - taux_reussite, 2) if total_inscrits > 0 else 100

        cursor.execute("SELECT COUNT(*) FROM enseignants")
        total_enseignants = cursor.fetchone()[0]

        # ✅ Intentions
        if intent == "nombre_etudiants":
            return f"Il y a {total_etudiants} étudiants au total."
        if intent == "nombre_inscrits":
            return f"Il y a {total_inscrits} étudiants inscrits."
        if intent == "taux_inscription":
            return f"Le taux d'inscription est de {taux_inscription} %."
        if intent == "taux_reussite":
            return f"Le taux de réussite est de {taux_reussite} %."
        if intent == "taux_echec":
            return f"Le taux d'échec est de {taux_echec} %."
        if intent == "admis":
            return f"{total_admis} étudiants ont la moyenne (>=10)."
        if intent == "nombre_departements":
            cursor.execute("SELECT COUNT(*) FROM departement")
            nb_dep = cursor.fetchone()[0]
            return f"Il y a {nb_dep} départements."
        if intent == "liste_departements":
            cursor.execute("SELECT nom_departement FROM departement")
            deps = [d[0] for d in cursor.fetchall()]
            return f"Départements : {', '.join(deps)}"
        if intent == "nombre_filiere":
            cursor.execute("SELECT COUNT(*) FROM filiere")
            nb_fil = cursor.fetchone()[0]
            return f"Il y a {nb_fil} filières."
        if intent == "liste_filieres":
            cursor.execute("SELECT nom_filiere FROM filiere")
            fils = [f[0] for f in cursor.fetchall()]
            return f"Filières : {', '.join(fils)}"
        if intent == "nombre_enseignants":
            return f"Il y a {total_enseignants} enseignants."
        if intent == "enseignants_permanents":
            cursor.execute("SELECT COUNT(*) FROM enseignants WHERE enseignant_statut = 'PERMANANT'")
            nb_perm = cursor.fetchone()[0]
            return f"Il y a {nb_perm} enseignants permanents."
        if intent == "enseignants_non_permanents":
            cursor.execute("SELECT COUNT(*) FROM enseignants WHERE enseignant_statut = 'NON_PERMANANT'")
            nb_non_perm = cursor.fetchone()[0]
            return f"Il y a {nb_non_perm} enseignants non permanents."

        cursor.close()
        cnx.close()

    except Exception as e:
        return f"Erreur DB : {str(e)}"

    return "Je ne comprends pas encore cette question, essayez par exemple : « taux de réussite », « nombre inscrits », « combien de filières »."

def main():
    try:
        raw_data = sys.stdin.read()
        data = json.loads(raw_data)
        question = data.get("question", "").strip()
        if not question:
            print(json.dumps({"response": "⚠️ Question vide."}, ensure_ascii=False))
            return
        answer = handle_chat(question)
        print(json.dumps({"response": answer}, ensure_ascii=False))
    except Exception as e:
        print(json.dumps({"response": f"⚠️ Erreur Python : {str(e)}"}, ensure_ascii=False))

if __name__ == "__main__":
    main()
