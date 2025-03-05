const express = require('express');
const mysql = require('mysql');
const fs = require('fs');
const { exec } = require('child_process');

const app = express();
app.use(express.urlencoded({ extended: true }));
app.use(express.json());

// Hardcoded API key - Sensitive Data Exposure
const API_KEY = "est_prod_9fdaf70d4eedfe"; 

// Database connection (hardcoded credentials)
const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'e757a232f8414eea667a657', // Hardcoded password
    database: 'users_db'
});

connection.connect();

// SQL Injection vulnerability
app.get('/user', (req, res) => {
    let userId = req.query.id;
    let query = `SELECT * FROM users WHERE id = '${userId}'`; // Vulnerable query
    connection.query(query, (err, results) => {
        if (err) throw err;
        res.json(results);
    });
});

// Cross-Site Scripting (XSS) vulnerability
app.post('/comment', (req, res) => {
    let comment = req.body.comment;
    res.send(`<h1>User Comment:</h1> ${comment}`); // No sanitization
});

// Command Injection vulnerability
app.get('/ping', (req, res) => {
    let ip = req.query.ip;
    exec(`ping -c 3 ${ip}`, (error, stdout, stderr) => {
        if (error) {
            res.send(`Error: ${stderr}`);
            return;
        }
        res.send(`Ping result: ${stdout}`);
    });
});

// Insecure File Access
app.get('/readfile', (req, res) => {
    let filename = req.query.file;
    fs.readFile(filename, 'utf8', (err, data) => {
        if (err) {
            res.status(500).send('Error reading file');
            return;
        }
        res.send(data);
    });
});

app.listen(3000, () => {
    console.log("Server running on port 3000");
});
