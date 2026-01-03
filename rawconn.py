import MySQLdb
import mysql.connector
from pyFuncs import pymsqlfunc 

conn = mysql.connector.connect(
    host = 'localhost',
    # port = 3306,
    password='ASHAY',
    user='root',
    database='user'
)

def connectToDB():
    if conn.is_connected():
        print("Connection Successful")
    else:
        print("Connection Failed")

    query = """
            INSERT INTO user
            (name, username,User_UID)
            VALUES (%s, %s, %s)
    """

    mycursor = conn.cursor()

    itt = int(input("enter number of ittrations: "))
    

    for i in range(itt):     
        
        name = input("enter name: ")
        username = input("enter username: ")
        User_UID = input("enter User_UID: ")

        val = (name, username, User_UID)
        # pysqlUserIn(Ashay, 3306, ASHAY, root, website)
        mycursor.execute(query, val)        
        conn.commit()

def addPwdToUser():
    pass


connectToDB()