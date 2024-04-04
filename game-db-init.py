import csv
from getpass import getpass
import sshtunnel
import pymysql

username = input("Enter your MySQL/SSH username: ")
password = getpass("Enter your MySQL password: ")
DATABASE_NAME = "tabletop_tavern"

# Connect to the MySQL database
with sshtunnel.SSHTunnelForwarder(
        ('storm.cise.ufl.edu', 22),
        ssh_username=username,
        remote_bind_address=('mysql.cise.ufl.edu', 3306)) as tunnel:
    db = pymysql.connect(host="127.0.0.1", user=username, password=password, database=DATABASE_NAME, port=tunnel.local_bind_port)
    cursor = db.cursor()

    # Open the CSV file
    with open('bgg_dataset.csv', 'r', encoding='utf-8-sig') as f:
        reader = csv.DictReader(f, delimiter=';')
        for row in reader:
            if row['ID'] == '' or row['Name'] == '' or row['Year Published'] == '' or row['Min Players'] == '' or row['Max Players'] == '' or row['Play Time'] == '' or row['Min Age'] == '' or row['Users Rated'] == '' or row['Rating Average'] == '' or row['Mechanics'] == '' or row['Domains'] == '':
                continue
            cursor.execute("""
                INSERT INTO Games (id, name, year_published, min_players, max_players, play_time, min_age, rating_count, rating_average)
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)
            """, (row['ID'], row['Name'], row['Year Published'], row['Min Players'], row['Max Players'], row['Play Time'],
                  row['Min Age'], row['Users Rated'], row['Rating Average'].replace(",", ".")))

            for mechanic in row['Mechanics'].split(', '):
                cursor.execute("""
                    INSERT INTO Mechanics (mechanic) VALUES (%s) ON DUPLICATE KEY UPDATE mechanic = mechanic
                """, (mechanic,))
                cursor.execute("""
                    SELECT id FROM Mechanics WHERE mechanic = %s
                """, (mechanic,))
                mechanic_id = cursor.fetchone()[0]
                cursor.execute("""
                    INSERT INTO GameMechanicConnection (game_id, mechanic_id) VALUES (%s, %s)
                """, (row['ID'], mechanic_id))

            for domain in row['Domains'].split(', '):
                cursor.execute("""
                    INSERT INTO Subgenre (subgenre) VALUES (%s) ON DUPLICATE KEY UPDATE subgenre = subgenre
                """, (domain,))
                cursor.execute("""
                    SELECT id FROM Subgenre WHERE subgenre = %s
                """, (domain,))
                domain_id = cursor.fetchone()[0]
                cursor.execute("""
                    INSERT INTO GameSubgenreConnection (game_id, subgenre_id) VALUES (%s, %s)
                """, (row['ID'], domain_id))

    # Commit the changes and close the connection
    db.commit()
    db.close()
