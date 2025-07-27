
# import sys
# sys.path.append(r"C:\Users\DELL\AppData\Roaming\Python\Python313\site-packages")
# import mysql.connector
# import json
# from decimal import Decimal
# import numpy as np
# from sklearn.linear_model import LinearRegression

# # 🔹 Configuration MySQL
# config = {
#     "host": "localhost",
#     "user": "root",
#     "password": "",
#     "database": "db_universite"
# }

# def convert_decimal(obj):
#     if isinstance(obj, dict):
#         return {k: convert_decimal(v) for k, v in obj.items()}
#     elif isinstance(obj, list):
#         return [convert_decimal(i) for i in obj]
#     elif isinstance(obj, Decimal):
#         return float(obj)
#     else:
#         return obj

# def connect_db():
#     return mysql.connector.connect(**config)

# # ✅ Fonctions stats (inchangées)
# def get_stats_dga(cursor):
#     stats = {
#         'taux_reussite': 0,
#         'evolution': '+0%',
#         'best_dep': {'nom': '0', 'taux': 0},
#         'worst_dep': {'nom': '0', 'taux': 0},
#         'total_etudiants': 0,
#         'total_inscrits': 0,
#         'taux_inscription': 0,
#         'taux_echec': 0
#     }

#     # 1. Total étudiants
#     cursor.execute("SELECT COUNT(*) as total FROM etudiant")
#     total_etudiants = cursor.fetchone()[0] or 0
#     stats['total_etudiants'] = total_etudiants

#     # 2. Total inscrits
#     cursor.execute("""
#         SELECT COUNT(DISTINCT e.id_etudiant)
#         FROM etudiant e
#         JOIN payement p ON p.idEtudt = e.id_etudiant
#         WHERE p.montant_paye > 0 AND p.date IS NOT NULL
#     """)
#     total_inscrits = cursor.fetchone()[0] or 0
#     stats['total_inscrits'] = total_inscrits

#     # 3. Taux inscription
#     stats['taux_inscription'] = round((total_inscrits / total_etudiants) * 100, 2) if total_etudiants > 0 else 0

#     # 4. Admis
#     cursor.execute("""
#         SELECT COUNT(DISTINCT n.id_etudiant)
#         FROM note_etudiant n
#         JOIN etudiant e ON e.id_etudiant = n.id_etudiant
#         JOIN payement p ON p.idEtudt = e.id_etudiant
#         WHERE n.moyenne_module >= 10 AND p.montant_paye > 0
#     """)
#     total_admis = cursor.fetchone()[0] or 0

#     # 5. Taux réussite
#     if total_inscrits > 0:
#         taux_reussite = round((total_admis / total_inscrits) * 100, 2)
#         taux_echec = round(100 - taux_reussite, 2)
#     else:
#         taux_reussite = 0
#         taux_echec = 100
#     stats['taux_reussite'] = taux_reussite
#     stats['taux_echec'] = taux_echec

#     # Evolution
#     stats['evolution'] = '+0.35%'

#     # Best/Worst département
#     cursor.execute("""
#         SELECT d.nom_departement, 
#                ROUND(IF(COUNT(DISTINCT e.id_etudiant) > 0,
#                         (COUNT(DISTINCT CASE WHEN n.moyenne_module >= 10 THEN e.id_etudiant END) / COUNT(DISTINCT e.id_etudiant)) * 100, 0), 2) AS taux
#         FROM departement d
#         LEFT JOIN filiere f ON f.id_departement = d.id_departement
#         LEFT JOIN promotion pr ON pr.id_filiere = f.id_filiere
#         LEFT JOIN etudiant e ON e.id_promotion = pr.id_promotion
#         LEFT JOIN note_etudiant n ON n.id_etudiant = e.id_etudiant
#         GROUP BY d.id_departement
#         ORDER BY taux DESC
#     """)
#     deps = cursor.fetchall()
#     if deps:
#         stats['best_dep'] = {'nom': deps[0][0], 'taux': float(deps[0][1])}
#         stats['worst_dep'] = {'nom': deps[-1][0], 'taux': float(deps[-1][1])}
#     return stats

# def get_stats_departements_detail(cursor):
#     cursor.execute("""
#         SELECT d.nom_departement, COUNT(DISTINCT e.id_etudiant), 
#                COUNT(DISTINCT CASE WHEN ne.moyenne_module >= 10 THEN e.id_etudiant END),
#                ROUND(IF(COUNT(DISTINCT e.id_etudiant) > 0,
#                         (COUNT(DISTINCT CASE WHEN ne.moyenne_module >= 10 THEN e.id_etudiant END) / COUNT(DISTINCT e.id_etudiant)) * 100, 0), 2)
#         FROM departement d
#         LEFT JOIN filiere f ON f.id_departement = d.id_departement
#         LEFT JOIN parcours pa ON pa.id_filiere = f.id_filiere
#         LEFT JOIN promotion p ON p.id_parcours = pa.id_parcours
#         LEFT JOIN etudiant e ON e.id_promotion = p.id_promotion
#         LEFT JOIN note_etudiant ne ON ne.id_etudiant = e.id_etudiant
#         GROUP BY d.id_departement
#     """)
#     rows = cursor.fetchall()
#     return [{"departement": r[0], "total_etudiants": r[1], "admis": r[2], "taux_reussite": float(r[3])} for r in rows]

# def prediction_taux(departements):
#     taux_list = [dep['taux_reussite'] for dep in departements if dep['taux_reussite'] is not None]
#     if len(taux_list) < 2:
#         return "Données insuffisantes"
#     X = np.arange(1, len(taux_list)+1).reshape(-1, 1)
#     y = np.array(taux_list)
#     return round(float(LinearRegression().fit(X, y).predict([[len(taux_list)+1]])[0]), 2)

# # ✅ Mode Chat
# def handle_chat(question, cursor):
#     q = question.lower()
#     if "taux de réussite" in q:
#         cursor.execute("""
#             SELECT COUNT(DISTINCT e.id_etudiant), 
#                    COUNT(DISTINCT CASE WHEN n.moyenne_module >= 10 THEN e.id_etudiant END)
#             FROM etudiant e
#             JOIN payement p ON p.idEtudt = e.id_etudiant
#             LEFT JOIN note_etudiant n ON n.id_etudiant = e.id_etudiant
#             WHERE p.montant_paye > 0
#         """)
#         total, admis = cursor.fetchone()
#         if total > 0:
#             taux = round((admis / total) * 100, 2)
#             return f"Le taux de réussite actuel est de {taux} %."
#         return "Aucun inscrit trouvé."
#     elif "combien d'étudiants" in q:
#         cursor.execute("SELECT COUNT(*) FROM etudiant")
#         total = cursor.fetchone()[0]
#         return f"Il y a actuellement {total} étudiants dans la base."
#     elif "meilleur département" in q:
#         cursor.execute("""
#             SELECT d.nom_departement, ROUND(IF(COUNT(DISTINCT e.id_etudiant) > 0,
#                         (COUNT(DISTINCT CASE WHEN n.moyenne_module >= 10 THEN e.id_etudiant END)/COUNT(DISTINCT e.id_etudiant))*100, 0), 2) AS taux
#             FROM departement d
#             LEFT JOIN filiere f ON f.id_departement = d.id_departement
#             LEFT JOIN promotion pr ON pr.id_filiere = f.id_filiere
#             LEFT JOIN etudiant e ON e.id_promotion = pr.id_promotion
#             LEFT JOIN note_etudiant n ON n.id_etudiant = e.id_etudiant
#             GROUP BY d.id_departement
#             ORDER BY taux DESC LIMIT 1
#         """)
#         dep = cursor.fetchone()
#         return f"Le meilleur département est {dep[0]} avec {dep[1]} % de réussite."
#     else:
#         return "Je ne comprends pas encore cette question."

# def main():
#     try:
#         data = None
#         if len(sys.argv) > 1:
#             try:
#                 data = json.loads(sys.argv[1])
#             except:
#                 pass

#         cnx = connect_db()
#         cursor = cnx.cursor()

#         if data and data.get("context") == "chat":
#             question = data.get("question", "")
#             print(handle_chat(question, cursor))
#         else:
#             stats = get_stats_dga(cursor)
#             departements = get_stats_departements_detail(cursor)
#             prediction = prediction_taux(departements)
#             result = {"stats": stats, "departements": departements, "prediction": prediction}
#             print(json.dumps(convert_decimal(result), ensure_ascii=False, indent=4))

#         cursor.close()
#         cnx.close()
#     except Exception as e:
#         print(json.dumps({"error": str(e)}, ensure_ascii=False))

# if __name__ == "__main__":
#     main()

import sys
sys.path.append(r"C:\Users\DELL\AppData\Roaming\Python\Python313\site-packages")
import mysql.connector
import json
from decimal import Decimal
import numpy as np
from sklearn.linear_model import LinearRegression

# 🔹 Configuration MySQL
config = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "db_universite"
}

def convert_decimal(obj):
    if isinstance(obj, dict):
        return {k: convert_decimal(v) for k, v in obj.items()}
    elif isinstance(obj, list):
        return [convert_decimal(i) for i in obj]
    elif isinstance(obj, Decimal):
        return float(obj)
    else:
        return obj

def connect_db():
    return mysql.connector.connect(**config)

# ✅ Fonctions statistiques
def get_stats_dga(cursor):
    stats = {
        'taux_reussite': 0,
        'evolution': '+0%',
        'best_dep': {'nom': '0', 'taux': 0},
        'worst_dep': {'nom': '0', 'taux': 0},
        'total_etudiants': 0,
        'total_inscrits': 0,
        'taux_inscription': 0,
        'taux_echec': 0
    }

    # Total étudiants
    cursor.execute("SELECT COUNT(*) FROM etudiant")
    stats['total_etudiants'] = cursor.fetchone()[0] or 0

    # Total inscrits
    cursor.execute("""
        SELECT COUNT(DISTINCT e.id_etudiant)
        FROM etudiant e
        JOIN payement p ON p.idEtudt = e.id_etudiant
        WHERE p.montant_paye > 0 AND p.date IS NOT NULL
    """)
    stats['total_inscrits'] = cursor.fetchone()[0] or 0

    # Taux inscription
    if stats['total_etudiants'] > 0:
        stats['taux_inscription'] = round((stats['total_inscrits'] / stats['total_etudiants']) * 100, 2)

    # Admis
    cursor.execute("""
        SELECT COUNT(DISTINCT n.id_etudiant)
        FROM note_etudiant n
        JOIN etudiant e ON e.id_etudiant = n.id_etudiant
        JOIN payement p ON p.idEtudt = e.id_etudiant
        WHERE n.moyenne_module >= 10 AND p.montant_paye > 0
    """)
    total_admis = cursor.fetchone()[0] or 0

    # Taux réussite et échec
    if stats['total_inscrits'] > 0:
        taux_reussite = round((total_admis / stats['total_inscrits']) * 100, 2)
    else:
        taux_reussite = 0
    stats['taux_reussite'] = taux_reussite
    stats['taux_echec'] = round(100 - taux_reussite, 2)

    # Évolution fictive
    stats['evolution'] = '+0.35%'

    # Best/Worst département
    cursor.execute("""
        SELECT d.nom_departement, 
               ROUND(IF(COUNT(DISTINCT e.id_etudiant) > 0,
                        (COUNT(DISTINCT CASE WHEN n.moyenne_module >= 10 THEN e.id_etudiant END) / COUNT(DISTINCT e.id_etudiant)) * 100, 0), 2) AS taux
        FROM departement d
        LEFT JOIN filiere f ON f.id_departement = d.id_departement
        LEFT JOIN promotion pr ON pr.id_filiere = f.id_filiere
        LEFT JOIN etudiant e ON e.id_promotion = pr.id_promotion
        LEFT JOIN note_etudiant n ON n.id_etudiant = e.id_etudiant
        GROUP BY d.id_departement
        ORDER BY taux DESC
    """)
    deps = cursor.fetchall()
    if deps:
        stats['best_dep'] = {'nom': deps[0][0], 'taux': float(deps[0][1])}
        stats['worst_dep'] = {'nom': deps[-1][0], 'taux': float(deps[-1][1])}
    return stats

def get_stats_departements_detail(cursor):
    cursor.execute("""
        SELECT d.nom_departement, COUNT(DISTINCT e.id_etudiant), 
               COUNT(DISTINCT CASE WHEN ne.moyenne_module >= 10 THEN e.id_etudiant END),
               ROUND(IF(COUNT(DISTINCT e.id_etudiant) > 0,
                        (COUNT(DISTINCT CASE WHEN ne.moyenne_module >= 10 THEN e.id_etudiant END) / COUNT(DISTINCT e.id_etudiant)) * 100, 0), 2)
        FROM departement d
        LEFT JOIN filiere f ON f.id_departement = d.id_departement
        LEFT JOIN parcours pa ON pa.id_filiere = f.id_filiere
        LEFT JOIN promotion p ON p.id_parcours = pa.id_parcours
        LEFT JOIN etudiant e ON e.id_promotion = p.id_promotion
        LEFT JOIN note_etudiant ne ON ne.id_etudiant = e.id_etudiant
        GROUP BY d.id_departement
    """)
    rows = cursor.fetchall()
    return [{"departement": r[0], "total_etudiants": r[1], "admis": r[2], "taux_reussite": float(r[3])} for r in rows]

def prediction_taux(departements):
    taux_list = [dep['taux_reussite'] for dep in departements if dep['taux_reussite'] is not None]
    if len(taux_list) < 2:
        return "Données insuffisantes"
    X = np.arange(1, len(taux_list)+1).reshape(-1, 1)
    y = np.array(taux_list)
    return round(float(LinearRegression().fit(X, y).predict([[len(taux_list)+1]])[0]), 2)

# ✅ Mode Chat amélioré
def handle_chat(question, cursor):
    q = question.lower().strip()

    # Salutation
    if any(word in q for word in ["salut", "bonjour", "hello", "coucou", "hi"]):
        return "👋 Bonjour ! Je peux vous donner :\n- ✅ Le taux de réussite\n- ✅ Le meilleur département\n- ✅ Le nombre d'étudiants\nQue souhaitez-vous savoir ?"

    # Taux de réussite
    if "taux" in q and "réussite" in q:
        cursor.execute("""
            SELECT COUNT(DISTINCT e.id_etudiant), 
                   COUNT(DISTINCT CASE WHEN n.moyenne_module >= 10 THEN e.id_etudiant END)
            FROM etudiant e
            JOIN payement p ON p.idEtudt = e.id_etudiant
            LEFT JOIN note_etudiant n ON n.id_etudiant = e.id_etudiant
            WHERE p.montant_paye > 0
        """)
        total, admis = cursor.fetchone()
        if total > 0:
            taux = round((admis / total) * 100, 2)
            return f"📊 Le taux de réussite actuel est de **{taux} %**."
        return "⚠️ Aucun inscrit trouvé."

    # Meilleur département
    if "meilleur" in q and "département" in q:
        cursor.execute("""
            SELECT d.nom_departement, ROUND(IF(COUNT(DISTINCT e.id_etudiant) > 0,
                        (COUNT(DISTINCT CASE WHEN n.moyenne_module >= 10 THEN e.id_etudiant END)/COUNT(DISTINCT e.id_etudiant))*100, 0), 2) AS taux
            FROM departement d
            LEFT JOIN filiere f ON f.id_departement = d.id_departement
            LEFT JOIN promotion pr ON pr.id_filiere = f.id_filiere
            LEFT JOIN etudiant e ON e.id_promotion = pr.id_promotion
            LEFT JOIN note_etudiant n ON n.id_etudiant = e.id_etudiant
            GROUP BY d.id_departement
            ORDER BY taux DESC LIMIT 1
        """)
        dep = cursor.fetchone()
        if dep:
            return f"🏆 Le meilleur département est **{dep[0]}** avec **{dep[1]} %** de réussite."
        return "⚠️ Impossible de déterminer le meilleur département."

    # Nombre d'étudiants
    if "combien" in q and "étudiants" in q:
        cursor.execute("SELECT COUNT(*) FROM etudiant")
        total = cursor.fetchone()[0]
        return f"👨‍🎓 Il y a actuellement **{total} étudiants** dans la base."

    # Réponse par défaut
    return "❓ Je ne comprends pas encore cette question.\nEssayez : 'Quel est le taux de réussite ?' ou 'Quel est le meilleur département ?'"
    
# ✅ Programme principal
def main():
    try:
        data = None
        if len(sys.argv) > 1:
            try:
                data = json.loads(sys.argv[1])
            except:
                pass

        cnx = connect_db()
        cursor = cnx.cursor()

        if data and data.get("context") == "chat":
            question = data.get("question", "")
            print(handle_chat(question, cursor))
        else:
            stats = get_stats_dga(cursor)
            departements = get_stats_departements_detail(cursor)
            prediction = prediction_taux(departements)
            result = {"stats": stats, "departements": departements, "prediction": prediction}
            print(json.dumps(convert_decimal(result), ensure_ascii=False, indent=4))

        cursor.close()
        cnx.close()
    except Exception as e:
        print(json.dumps({"error": str(e)}, ensure_ascii=False))

if __name__ == "__main__":
    main()
