const mysql = require('mysql');
const con = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'password',
    database: 'testidatabase'
});

con.query('SELECT * FROM employees', (err,rows) => {
    if(err) throw err;

    console.log('Data received from Db:\n');
    console.log(rows);
});
