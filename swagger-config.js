module.exports = {
    definition: {
      openapi: '3.0.0',
      info: {
        title: 'Rental API',
        version: '1.0.0',
        description: 'Documentation for the Rental API',
      },
      servers: [
        {
          url: 'http://localhost:9000/info', // Adjust the base URL based on your setup
          description: 'Local Development Server',
        },
      ],
    },
    apis: ['./src/handlers.js'], // Adjust the path to your API handlers
  };
  