'use strict';

/* Requires */
const config = require('./config.json').mailchimp;
const md5 = require('md5');
const Mailchimp = require('mailchimp-api-v3');
const mc = new Mailchimp(config.key);


/* Exports */
module.exports.addMember = (data) => {
  let hash = md5(data.user.email.toLowerCase());
  let company = data.company;
  let user = data.user;

  if(company) {
    company.categories = company.categories.join(', ');
    company.availability = company.availability.join(', ');
    company.main_activity = company.main_activity.join(', ');
  } else {
    company = {};
  }

  mc.put({
    path: `/lists/${config.list}/members/${hash}`,
    body: {
      email_address: user.email,
      email_type: 'html',
      status: 'subscribed',
      merge_fields: {
        FNAME:    user.name,
        PHONE:    user.phone,
        CNAME:    company.name          || '',
        CPFCNPJ:  company.cpf_cnpj      || '',
        SITE:     company.website       || '',
        BRANDS:   company.brands        || '',
        PRODS:    company.categories    || '',
        OTHERS:   company.others        || '',
        AVAIL:    company.availability  || '',
        ACTIV:    company.main_activity || ''
      }
    }
  })
  .then((result) => {
    console.log(`${user.email} saved to MailChimp`);
  })
  .catch((error) => {
    console.log(error);
  });
};