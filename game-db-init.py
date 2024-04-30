import csv
from getpass import getpass
import sshtunnel
import pymysql

DATASET_FILE = "bgg_dataset.csv"
DATABASE_NAME = "tabletop_tavern"
DATABASE_HOST = "localhost"
NEEDS_SSH_TUNNEL = False
SSH_HOST = "storm.cise.ufl.edu"

username = input("Enter your MySQL/SSH username: ")
password = getpass("Enter your MySQL password: ")


def init_db(sqlDb):
    cursor = sqlDb.cursor()

    # Open the CSV file
    with open(DATASET_FILE, 'r', encoding='utf-8-sig') as f:
        reader = csv.DictReader(f, delimiter=';')
        for row in reader:
            if row['ID'] == '' or row['Name'] == '' or row['Year Published'] == '' or row['Min Players'] == '' or row[
                'Max Players'] == '' or row['Play Time'] == '' or row['Min Age'] == '' or row['Users Rated'] == '' or \
                    row['Rating Average'] == '' or row['Mechanics'] == '' or row['Domains'] == '':
                continue
            try:
                cursor.execute("""
                        INSERT INTO games (id, name, year_published, min_players, max_players, play_time, min_age, rating_count, rating_average)
                        VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)
                    """, (
                    row['ID'], row['Name'], row['Year Published'], row['Min Players'], row['Max Players'],
                    row['Play Time'],
                    row['Min Age'], row['Users Rated'], row['Rating Average'].replace(",", ".")))
            except pymysql.err.IntegrityError:  # skip already existing games
                continue

            for mechanic in row['Mechanics'].split(', '):
                cursor.execute("""
                        INSERT INTO mechanics (mechanic) VALUES (%s) ON DUPLICATE KEY UPDATE mechanic = mechanic
                    """, (mechanic,))
                cursor.execute("""
                        SELECT id FROM mechanics WHERE mechanic = %s
                    """, (mechanic,))
                mechanic_id = cursor.fetchone()[0]
                cursor.execute("""
                        INSERT INTO gamemechanicconnection (game_id, mechanic_id) VALUES (%s, %s)
                    """, (row['ID'], mechanic_id))

            for domain in row['Domains'].split(', '):
                cursor.execute("""
                        INSERT INTO subgenre (subgenre) VALUES (%s) ON DUPLICATE KEY UPDATE subgenre = subgenre
                    """, (domain,))
                cursor.execute("""
                        SELECT id FROM subgenre WHERE subgenre = %s
                    """, (domain,))
                domain_id = cursor.fetchone()[0]
                cursor.execute("""
                        INSERT INTO gamesubgenreconnection (game_id, subgenre_id) VALUES (%s, %s)
                    """, (row['ID'], domain_id))

    # Commit the changes and close the connection
    sqlDb.commit()
    sqlDb.close()


# Connect to the MySQL database
if NEEDS_SSH_TUNNEL:
    with sshtunnel.SSHTunnelForwarder(
            (SSH_HOST, 22),
            ssh_username=username,
            remote_bind_address=(DATABASE_HOST, 3306)) as tunnel:
        db = pymysql.connect(host="127.0.0.1", user=username, password=password, database=DATABASE_NAME,
                             port=tunnel.local_bind_port)
        init_db(db)
else:
    db = pymysql.connect(host=DATABASE_HOST, user=username, password=password, database=DATABASE_NAME)
    init_db(db)
