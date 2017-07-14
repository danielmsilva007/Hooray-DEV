'use strict';

/* Requires */
const mongoose = require('mongoose');
const Schema = mongoose.Schema;


/* Model */
const SubscriptionSchema = new Schema({
  user: {
    name: {
      type: String,
      required: true
    },
    email: {
      type: String,
      trim: true,
      lowercase: true,
      index: true,
      required: true
    },
    phone: {
      type: String,
      required: true
    }
  },
  company: {
    name: {
      type: String
    },
    cpf_cnpj: {
      type: String
    },
    website: {
      type: String
    },
    brands: {
      type: String
    },
    categories: {
      type: Array
    },
    others: {
      type: String
    },
    availability: {
      type: Array
    },
    main_activity: {
      type: Array
    }
  },
  registered: {
    type: Date,
    default: Date.now()
  }
})

/* Exports */
module.exports = mongoose.model('Subscription', SubscriptionSchema);