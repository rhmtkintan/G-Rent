const Hapi = require('@hapi/hapi');
const Inert = require('@hapi/inert');
const Path = require('path');
const HapiCors = require('hapi-cors'); // Add this line
const swaggerJsdoc = require('swagger-jsdoc');
const swaggerUi = require('swagger-ui-dist');
const routes = require('./src/routes');

const init = async () => {
  const server = Hapi.server({
    port: 9000,
    host: 'localhost',
    routes: {
      files: {
        relativeTo: Path.join(__dirname, 'node_modules/swagger-editor-dist'),
      },
      cors: true, // Enable CORS globally for all routes
    },
  });

  await server.register([Inert, HapiCors]); // Add HapiCors to the plugins

  // Define Swagger options
  const swaggerOptions = require('./swagger-config.js');
  const specs = swaggerJsdoc(swaggerOptions);

  // Serve Swagger UI at /docs endpoint
  server.route({
    method: 'GET',
    path: '/{param*}',
    handler: {
      directory: {
        path: '.',
        redirectToSlash: true,
      },
    },
  });

  // Serve Swagger JSON at /docs-json endpoint
  server.route({
    method: 'GET',
    path: '/docs-json',
    handler: (request, h) => {
      return h.response(specs).header('Content-Type', 'application/json');
    },
  });

  server.route(routes);
  await server.start();
  console.log(`Server berjalan di ---> ${server.info.uri} \nTekan Ctrl+C untuk menghentikan servermu`);
};

process.on('unhandledRejection', (err) => {
  console.log(err);
  process.exit(1);
});

init();
