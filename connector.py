from flask import Flask, request, render_template
import mysql.connector
import hashlib

app = Flask(__name__)

# Database connection
mydb = mysql.connector.connect(
    host="localhost",
    port=3307,
    user="root",
    password="ASHAY",
    database="website"
)
mycursor = mydb.cursor()

@app.route("/")
def home():
    return render_template("register.html")

def connectToDB():
    if mydb.is_connected():
        print("Connection Successful")
    else:
        print("Connection Failed")

@app.route("/register", methods=["POST"])
def register():
    first_name = request.form["first_name"]
    last_name = request.form["last_name"]
    username = request.form["username"]
    phone = request.form["phone"]
    email = request.form["email"]
    password = request.form["password"]

    query = """
    INSERT INTO user_credentials (first_name, last_name, username, phone, email, password)
    VALUES (%s, %s, %s, %s, %s, %s)
    """
    mycursor.execute(query, (first_name, last_name, username, phone, email, password))
    mydb.commit()

    return "Registration Successful!"

if __name__ == "__main__":
    app.run(debug=True)



@app.route("/login", methods=["POST"])
def login():
    user = request.form["username"]
    pwd = hashlib.sha256(request.form["password"].encode()).hexdigest()

    # Use the existing global connection and cursor
    mycursor.execute("SELECT * FROM users WHERE username=%s AND password=%s", (user, pwd))
    result = mycursor.fetchone()

    if result:
        return "Login successful!"
    return "Invalid username or password."