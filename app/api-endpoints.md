Authentication Endpoints:

POST /oauth/access_token
GET  /users/login
POST /users/forget_password
GET  /users/reset_password
POST /users/USER_ID/update

GET  /jobs  ## Allows filtering
POST /jobs  ## Create Jobs
GET  /jobs/JOB_ID/
POST /jobs/JOB_ID/update
POST /jobs/JOB_ID/apply
GET  /jobs/JOB_ID/applicants

GET  /candidates
POST /candidates/register
GET  /candidates/CANDIDATE_ID
POST /candidates/CANDIDATE_ID/update
GET  /candidates/CANDIDATE_ID/jobs  # job applied


POST /agencies/register
GET  /agencies/AGENCY_ID
POST /agencies/AGENCY_ID/update
GET  /agencies/AGENCY_ID/jobs
GET  /agencies/AGENCY_ID/agents
POST /agencies/AGENCY_ID/agents/register
POST /agencies/AGENCY_ID/agents/AGENT_ID
POST /agencies/AGENCY_ID/agents/AGENT_ID/update
POST /agencies/AGENCY_ID/jobs/JOB_ID/assign/AGENT_ID
