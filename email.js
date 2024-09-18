const nodemailer = require('nodemailer');

const sendEmail = (recipient, subject, text, attachments) => {
  const transporter = nodemailer.createTransport({
    service: 'gmail', 
    auth: {
      user: 'tarikismail600@gmail.com',
      pass: 'tarik2000ismail',
    },
  });

  const mailOptions = {
    from: 'tarikismail600@gmail.com',
    to: recipient,
    subject: subject,
    text: text,
    attachments: attachments,
  };

  transporter.sendMail(mailOptions, (error, info) => {
    if (error) {
      return console.log(error);
    }
    console.log('Email sent: ' + info.response);
  });
};

module.exports = { sendEmail };



// email.js

const nodemailer = require('nodemailer');

const recipientEmail = process.argv[2];  // Email du destinataire
const subject = process.argv[3];  // Sujet de l'email
const text = process.argv[4];  // Contenu de l'email

// Configurez Nodemailer pour envoyer l'email
let transporter = nodemailer.createTransport({
    host: "smtp.gmail.com",
    port: 587,
    secure: false, // true for 465, false for other ports
    auth: {
        user: 'tarikismail600@gmail.com',
        pass: '' 
    }
});

const mailOptions = {
    from: 'tarikismail600@gmail.com',
    to: recipientEmail,
    subject: subject,
    text: text
};

transporter.sendMail(mailOptions, (error, info) => {
    if (error) {
        return console.log(error);
    }
    console.log('Email envoyé: ' + info.response);
});
