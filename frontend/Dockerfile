# syntax=docker/dockerfile:1

# -------------------------------------------
# Base stage with committed Yarn binary
# -------------------------------------------
FROM node:24-alpine AS base

WORKDIR /app

# Copy Yarn binary and config first (for caching)
COPY .yarn/releases ./.yarn/releases
COPY .yarnrc.yml ./

# Copy package files
COPY package.json yarn.lock ./

# -------------------------------------------
# Development stage
# -------------------------------------------
FROM base AS development

# Install all dependencies using committed Yarn binary
RUN node .yarn/releases/yarn-4.12.0.cjs install --immutable

# Copy application source
COPY . .

EXPOSE 5173

CMD ["node", ".yarn/releases/yarn-4.12.0.cjs", "dev", "--host", "0.0.0.0"]

# -------------------------------------------
# Build stage (for production)
# -------------------------------------------
FROM base AS build-env

RUN node .yarn/releases/yarn-4.12.0.cjs install --immutable
COPY . .
RUN node .yarn/releases/yarn-4.12.0.cjs build

# -------------------------------------------
# Production stage
# -------------------------------------------
FROM node:24-alpine AS production

WORKDIR /app

# Copy Yarn binary and config
COPY .yarn/releases ./.yarn/releases
COPY .yarnrc.yml package.json yarn.lock ./

# Install production dependencies only
RUN node .yarn/releases/yarn-4.12.0.cjs install --immutable

# Copy built assets from build stage
COPY --from=build-env /app/build ./build

EXPOSE 3000

CMD ["node", ".yarn/releases/yarn-4.12.0.cjs", "start"]
