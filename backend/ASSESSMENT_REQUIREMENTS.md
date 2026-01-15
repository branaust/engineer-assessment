# Assessment Requirements Checklist

Use this checklist to track your progress through the assessment requirements.

## Core Requirements

### 1. Data Management
- [ ] Fetch data from SWAPI during seeding
- [ ] Store people in database
- [ ] Store films in database
- [ ] Store relationships (many-to-many between people and films)
- [ ] Application works offline (after initial seeding)
- [ ] Handle SWAPI errors gracefully
- [ ] Consider rate limiting when fetching data

### 2. API Implementation (Choose REST or GraphQL or Both)

#### REST API (Option A)
- [ ] `GET /api/people` - List all people
- [ ] `GET /api/people?search=query` - Search people
- [ ] `GET /api/people/{id}` - Get person with films
- [ ] `POST /api/people` - Create person
- [ ] `PUT /api/people/{id}` - Update person
- [ ] `DELETE /api/people/{id}` - Delete person
- [ ] `GET /api/films` - List all films
- [ ] `GET /api/films?search=query` - Search films
- [ ] `GET /api/films/{id}` - Get film with people
- [ ] `POST /api/films` - Create film
- [ ] `PUT /api/films/{id}` - Update film
- [ ] `DELETE /api/films/{id}` - Delete film

#### GraphQL (Option B)
- [ ] Define Person type with relationships
- [ ] Define Film type with relationships
- [ ] Query: people (with search)
- [ ] Query: person(id)
- [ ] Query: films (with search)
- [ ] Query: film(id)
- [ ] Mutation: createPerson
- [ ] Mutation: updatePerson
- [ ] Mutation: deletePerson
- [ ] Mutation: createFilm
- [ ] Mutation: updateFilm
- [ ] Mutation: deleteFilm

### 3. Statistics & Background Processing
- [ ] Track searches in `search_logs` table
- [ ] Log: query, resource_type, duration_ms, searched_at
- [ ] Create background job for statistics calculation
- [ ] Schedule job to run every 5 minutes
- [ ] `GET /api/statistics` endpoint returns:
  - [ ] Average request duration (milliseconds)
  - [ ] Most popular hour of day (0-23)
  - [ ] Top 5 search queries with percentages
- [ ] Statistics are cached (only update every 5 min)

### 4. Testing
- [ ] Feature tests for CRUD operations
- [ ] Feature tests for search functionality
- [ ] Unit tests for SwapiService
- [ ] Unit tests for StatisticsService
- [ ] Tests use factories for data generation
- [ ] All tests pass (`docker compose exec backend php artisan test`)

### 5. Code Quality
- [ ] Clean, readable code
- [ ] Appropriate use of services/repositories
- [ ] Proper error handling
- [ ] Input validation
- [ ] Database queries are optimized (eager loading, indexes)
- [ ] Code follows PSR standards (run `docker compose exec backend ./vendor/bin/pint`)
- [ ] Meaningful comments where needed

## Documentation Requirements

### Required Files

#### DECISIONS.md
- [ ] Document 3-5 key architectural decisions
- [ ] Use the format: Context, Options, Decision, Consequences
- [ ] Cover: Database schema, API design, Statistics approach
- [ ] Explain trade-offs clearly

#### SCALING.md
- [ ] Answer: How to handle 100k searches/day?
- [ ] Answer: What if dataset grows 50x?
- [ ] Answer: What to monitor in production?
- [ ] Answer: What to document for next engineer?
- [ ] Each answer is 1-2 paragraphs

#### README.md (Update if needed)
- [ ] Document any changes to structure
- [ ] Update setup instructions if modified
- [ ] Note any assumptions or limitations
- [ ] List technologies/libraries added

## Evaluation Criteria

### Architectural Thinking (35%)
- [ ] Database schema is well-designed
- [ ] API design is RESTful/follows GraphQL best practices
- [ ] Queue implementation is appropriate
- [ ] Statistics tracking is efficient
- [ ] DECISIONS.md shows sound reasoning
- [ ] SCALING.md shows system design thinking

### Code Quality (30%)
- [ ] Code is clean and maintainable
- [ ] Appropriate abstractions (services, repositories)
- [ ] Error handling is comprehensive
- [ ] Testing approach is strategic
- [ ] No obvious code smells

### Full-Stack Execution (25%)
- [ ] All CRUD operations work correctly
- [ ] Search functionality is implemented
- [ ] Relationships are handled properly
- [ ] Statistics endpoint works correctly
- [ ] Background jobs process correctly
- [ ] API responses are well-structured

### Documentation & Communication (10%)
- [ ] DECISIONS.md is clear and insightful
- [ ] SCALING.md shows production thinking
- [ ] Code comments are helpful where needed
- [ ] README is updated appropriately

## Pre-Submission Checklist

### Functionality
- [ ] Can run `docker compose up -d` successfully
- [ ] Can run `docker compose exec backend php artisan migrate:fresh --seed` successfully
- [ ] All API endpoints return expected responses
- [ ] Search functionality works
- [ ] Statistics endpoint returns correct data
- [ ] Queue worker processes jobs
- [ ] Tests pass

### Code Quality
- [ ] Removed debug code and console.logs
- [ ] Run `docker compose exec backend ./vendor/bin/pint` for code formatting
- [ ] No sensitive data in code
- [ ] .env is not committed

### Documentation
- [ ] DECISIONS.md is complete
- [ ] SCALING.md is complete
- [ ] README.md is updated (if needed)
- [ ] Code has helpful comments

### Testing on Fresh Setup
- [ ] Delete `.env` and recreate from `.env.example`
- [ ] Run `docker compose down -v` to remove volumes
- [ ] Run `docker compose up -d` to start fresh
- [ ] Run setup commands and verify everything works
- [ ] Have someone else test your setup (if possible)

## Submission

- [ ] Code is in a private GitHub repository
- [ ] Repository access granted to LawnStarter team
- [ ] Repository includes:
  - [ ] All source code
  - [ ] DECISIONS.md
  - [ ] SCALING.md
  - [ ] Updated README.md
  - [ ] Working Docker setup
- [ ] Email sent to paulo.venturi@lawnstarter.com with repo link

## Optional Enhancements (Not Required)

These are NOT required but might showcase your skills:

- [ ] API rate limiting
- [ ] API documentation (Swagger/OpenAPI)
- [ ] Request/Response logging middleware
- [ ] Advanced caching strategy
- [ ] Database query optimization
- [ ] Comprehensive error handling
- [ ] API versioning
- [ ] Docker optimization
- [ ] CI/CD configuration
- [ ] Performance monitoring setup

**Remember:** Quality over quantity. A well-implemented core solution is better than a partially-implemented solution with many features.

---

**Estimated Time:** 8-10 hours

**Due:** One week from receiving the assessment

**Questions?** Contact paulo.venturi@lawnstarter.com
