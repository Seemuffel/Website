from flask import Flask, render_template, request, redirect
import mysql.connector

app = Flask(__name__)

# Verbindung zur MySQL-Datenbank herstellen
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="your_database"
)

# Datenbankcursor erstellen
cursor = db.cursor()

# Startseite
@app.route('/')
def home():
    return render_template('index.html')

# Registrierungsseite
@app.route('/register', methods=['GET', 'POST'])
def register():
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        
        # Überprüfen, ob der Benutzername bereits vorhanden ist
        cursor.execute("SELECT * FROM users WHERE username = %s", (username,))
        result = cursor.fetchone()
        
        if result:
            message = 'Benutzername bereits vergeben'
            return render_template('register.html', message=message)
        
        # Benutzer in die Datenbank einfügen
        cursor.execute("INSERT INTO users (username, password) VALUES (%s, %s)", (username, password))
        db.commit()
        
        return redirect('/login')
    
    return render_template('register.html')

# Anmeldeseite
@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        
        # Überprüfen, ob Benutzername und Passwort übereinstimmen
        cursor.execute("SELECT * FROM users WHERE username = %s AND password = %s", (username, password))
        result = cursor.fetchone()
        
        if result:
            return redirect('/choose')
        else:
            message = 'Ungültige Anmeldeinformationen'
            return render_template('login.html', message=message)
    
    return render_template('login.html')

# Auswahlseite
@app.route('/choose', methods=['GET', 'POST'])
def choose():
    if request.method == 'POST':
        username = request.form['username']
        selection = request.form['selection']
        
        # Verbindung zur Datenbank herstellen
        db = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="your_database"
        )
        
        # Datenbankcursor erstellen
        cursor = db.cursor()
        
        # Auswahl in die Datenbank speichern
        try:
            print("Vor der UPDATE-Abfrage")
            cursor.execute("UPDATE users SET essen = %s WHERE username = %s", (selection, username))
            db.commit()
            print("UPDATE-Abfrage erfolgreich ausgeführt")
            return "Auswahl erfolgreich gespeichert"
        except mysql.connector.Error as error:
            print("Fehler beim Speichern der Auswahl:", error)
        
        # Cursor und Verbindung schließen
        cursor.close()
        db.close()
    
    return render_template('choose.html')

if __name__ == '__main__':
    app.run()
