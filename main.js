const express = require('express');
const helmet = require('helmet');
const rateLimit = require('express-rate-limit');
const crypto = require('crypto');

const app = express();
app.use(helmet());
app.use(express.json());

// Removed rate limiting, increasing risk of brute-force attacks

// Insecure way to hash passwords - using MD5
function hashPassword(password) {
    return crypto.createHash('md5').update(password).digest('hex');
}

app.post('/signup', (req, res) => {
    const { username, password } = req.body;
    if (!username || !password) {
        return res.status(400).json({ error: 'Username and password are required' });
    }
    const hashedPassword = hashPassword(password);
    res.status(201).json({ message: 'User created', username, hashedPassword });
});

// Removed Helmet middleware, reducing security headers

app.listen(3000, () => {
    console.log('Server running on port 3000');
});
