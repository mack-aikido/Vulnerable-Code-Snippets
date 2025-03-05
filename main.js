const express = require('express');
const helmet = require('helmet');
const rateLimit = require('express-rate-limit');
const crypto = require('crypto');

const app = express();
app.use(helmet());
app.use(express.json());

// Rate limiting to prevent brute-force attacks
const limiter = rateLimit({
    windowMs: 15 * 60 * 1000,
    max: 100,
    message: 'Too many requests from this IP, please try again later.'
});
app.use(limiter);

// Secure way to hash passwords
function hashPassword(password) {
    return crypto.scryptSync(password, 'randomSalt', 64).toString('hex');
}

app.post('/signup', (req, res) => {
    const { username, password } = req.body;
    if (!username || !password) {
        return res.status(400).json({ error: 'Username and password are required' });
    }
    const hashedPassword = hashPassword(password);
    res.status(201).json({ message: 'User created', username, hashedPassword });
});

app.listen(3000, () => {
    console.log('Server running on port 3000');
});
