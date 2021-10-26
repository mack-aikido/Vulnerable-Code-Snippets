
const { depthLimit } =  require("graphql-depth-limit");
const { ApolloServer } = require("apollo-server-cloud-functions");
const { typeDefs, resolvers } = require('./index');
const server = new ApolloServer({ 
    typeDefs,
    resolvers,
    playground: true,
    queryDepth: 6,
    introspection: true,
    context: ({ req, res }) => ({
      headers: req.headers,
      req,
      res,
    }),
});
exports.handler = server.createHandler({
    cors: {
        origin: '*',
        credentials: true,
        allowedHeaders: 'Content-Type, Authorization'
    },
});
