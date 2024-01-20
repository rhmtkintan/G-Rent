const {
    getContactInfoHandler,
    addBookingHandler,
    getAllBookingsHandler,
  getBookingByIdHandler,
    updateBookingByIdHandler,
    deleteBookingByIdHandler,
    updateContactInfoHandler,
getAllGadgetsHandler,
getGadgetByIdHandler,
  } = require('./handler');
  
  const routes = [
    {
      method: 'POST',
      path: '/bookings',
      handler: addBookingHandler,
      options: {
        cors: {
          origin: ['*'],
        },
      },
    },
    {
        method: 'GET',
        path: '/info',
        handler: getContactInfoHandler,
      },

      {
        method: 'PUT',
        path: '/info',
        handler: updateContactInfoHandler,
      },

    {
      method: 'GET',
      path: '/bookings',
      handler: getAllBookingsHandler,
    },
    {
      method: 'GET',
      path: '/bookings/{id}',
      handler: getBookingByIdHandler,
    },
    {
      method: 'PUT',
      path: '/bookings/{id}',
      handler: updateBookingByIdHandler,
    },
    {
      method: 'DELETE',
      path: '/bookings/{id}',
      handler: deleteBookingByIdHandler,
    },
    {
      method: 'GET',
      path: '/gadgets',
      handler: getAllGadgetsHandler,
    },
    {
      method: 'GET',
      path: '/gadgets/{id}',
      handler: getGadgetByIdHandler,
    },
  ];
  
  module.exports = routes;