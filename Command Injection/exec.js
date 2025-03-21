const express = require('express');
const router = express.Router()

const { execFile, spawn }  = require('child_process');


router.post('/ping', (req,res) => {
    if (!/^[a-zA-Z0-9_\-\./\\]+$/.test(req.body.url)) throw new Error('Invalid input');
    execFile('ping', [req.body.url], (error) => {
        if (error) {
            return res.send('error');
        }
        res.send('pong')
    })
    
})

router.post('/gzip', (req,res) => {
    exec(
        'gzip ' + req.query.file_path,
        function (err, data) {
          console.log('err: ', err)
          console.log('data: ', data);
          res.send('done');
    });
})

router.get('/run', (req,res) => {
   let cmd = req.params.cmd;
   runMe(cmd,res)
});

function runMe(cmd,res){
//    return spawn(cmd);

    const cmdRunning = spawn(cmd, []);
    cmdRunning.on('close', (code) => {
        res.send(`child process exited with code ${code}`);
    });
}

module.exports = router
