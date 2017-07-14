'use strict';

/* Requires */
const config = require('./config.json');
const Subscription = require('./subscription.js');
const nodemailer = require('./nodemailer.js');
const mailchimp = require('./mailchimp.js');
const bodyParser = require('body-parser');
const mongoose = require('mongoose');
const express = require('express');
const path = require('path');
const app = express();


/* Variables */
const rootdir = path.join(__dirname, '../');
const mongo = config.mongo;

/* Database */
// Connection
mongoose.Promise = global.Promise;
mongoose.connect(`mongodb://${mongo.user}:${mongo.pass}@${mongo.host}:${mongo.port}/${mongo.db}`, {auth:{authdb:mongo.db}});
mongoose.connection.on('error', (error) => {
  console.error(`MongoDB Error: ${error}`);
});
mongoose.connection.once('open', () => {
  console.log('MongoDB Successfully connected');
});


/* Configuration */
app.use('/', express.static(rootdir));
app.use(bodyParser.urlencoded({extended: true}));
app.use(bodyParser.json());

app.route('/')
  .get((req, res) => {
    res.sendFile('index.html', {root: rootdir});
  });

app.route('/inscricao')
  .get((req, res) => {
    res.sendFile('inscricao.html', {root: rootdir});
  })
  .post((req, res) => {
    Subscription.findOneAndUpdate({'user.email': req.body.user.email},req.body, {upsert: true}, (error, data) => {
      if(error) {
        console.log(error);
        res.status(400).send(error);
      }

      console.log(`${req.body.user.email} saved to MongoDB`);
      nodemailer.sendEmail(req.body);
      mailchimp.addMember(req.body);
      res.status(200).send();
    });
  });

app.route('*')
  .get((req, res) => {
    res.redirect('/');
  });


/* Init */
app.listen(config.api.port, () => {
  console.log(`Listening on ${config.api.port}`);
});