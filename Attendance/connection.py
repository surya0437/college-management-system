from flask import Flask, render_template
from flask_mysqldb import MySQL

app = Flask(__name__)

app.config["MYSQL_HOST"] = "localhost"
app.config["MYSQL_USER"] = "root"
app.config["MYSQL_PASSWORD"] = ""
app.config["MYSQL_DB"] = "CollegeManagementSystem"

mysql = MySQL(app)


@app.route("/programs")
def fetch_programs():
    try:
        cur = mysql.connection.cursor()
        cur.execute("SELECT * FROM migrations")
        data = cur.fetchall()
        cur.close()
        # print(data[0][3])
        return render_template("programs.html", programs=data)
    except Exception as e:
        return f"Error: {str(e)}"


if __name__ == "__main__":
    app.run(debug=True)
