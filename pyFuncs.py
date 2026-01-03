import MySQLdb
import mysql.connector


class pymsqlfunc:

    def __init__(self, host, port, password, user, database):
            self.conn = mysql.connector.connect(
                host=host,
                port=port,
                password=password,
                user=user,
                database=database
            )


    
    def pysqlUserIn(self):
        cursor = self.conn.cursor()

        first_name = input("Enter your First Name: ")
        last_name = input("Enter your Last Name: ")
        username = input("Enter a UserName: ")
        phone = input("Enter your phone number: ")
        email = input("Enter an Email: ")
        password = input("Enter a Password: ")

        query = """
        INSERT INTO user_credentials 
        (first_name, last_name, username, phone, email, password)
        VALUES (%s, %s, %s, %s, %s, %s)
        """

        values = (first_name, last_name, username, phone, email, password)

        cursor.execute(query, values)
        self.conn.commit()

        return "Registration Successful!"