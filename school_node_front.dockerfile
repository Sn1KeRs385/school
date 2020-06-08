FROM node:8.11.4-alpine

ENV HOST 0.0.0.0
EXPOSE 3000

CMD npm install && npm run dev