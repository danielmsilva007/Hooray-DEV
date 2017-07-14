'use strict';

/* Requires */
const config = require('./config.json').nodemailer;
const nodemailer = require('nodemailer');
const transporter = nodemailer.createTransport({
  host: 'smtp.gmail.com',
  port: 587,
  secureConnection: true,
  auth: config.auth
});


/* Exports */
module.exports.sendEmail = (data) => {
  let user = data.user;
  let company = data.company;

  let options = {
    from: '"Syligo" <igor@syligo.com>',
    to: config.to,
    subject: `[Hooray | Pré-Inscrição] ${user.name} - Parte ${company ? '2' : '1'}`,
    html: `
      <h1>Hooray - Pré Inscrição</h1>
      <h2>${user.email} - Parte ${company ? '2' : '1'}</h2>
      <br>
      <p><b>Nome:</b> ${user.name}</p>
      <p><b>Email:</b> <a href='mailto:${user.email}'>${user.email}</a></p>
      <p><b>Telefone:</b> <a href='tel:${user.phone}'>${user.phone}</a></p>`
      + (company ? `
      <p><b>Nome da Empresa:</b> ${company.name}</p>
      <p><b>CPF/CNPJ:</b> ${company.cpf_cnpj}</p>
      <p><b>Website:</b> <a href='${company.website}' target='_blank'>${company.website}</a></p>
      <p><b>Marcas Comercializadas:</b> ${company.brands}</p>
      <p><b>Categorias de Produtos:</b> ${company.categories.join(', ')}</p>
      <p><b>Outros:</b> ${company.others}</p>
      <p><b>Disponibilidade do produto:</b> ${company.availability.join(', ')}</p>
      <p><b>Atividade Principal:</b> ${company.main_activity.join(', ')}</p>`
      : '')
  };

  transporter.sendMail(options, (error, info) => {
    if(error) {
      return console.log(error);
    }
    
    console.log(`${user.email} sent via NodeMailer`);
  });
};
