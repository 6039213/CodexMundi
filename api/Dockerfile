FROM node:20-alpine AS base
WORKDIR /usr/src/app
COPY package.json package-lock.json* yarn.lock* pnpm-lock.yaml* ./
RUN npm ci || yarn install || pnpm install
COPY . .
RUN npm run build || yarn build || pnpm build

FROM node:20-alpine
WORKDIR /usr/src/app
ENV NODE_ENV=production
COPY --from=base /usr/src/app/node_modules ./node_modules
COPY --from=base /usr/src/app/dist ./dist
EXPOSE 3000
CMD ["node", "dist/main.js"]

