import json
import google.auth
from google.oauth2 import service_account
import google.auth.transport.requests  

# Load the service account key'

SERVICE_ACCOUNT_FILE = './config/firebase-credentials.json'
SCOPES = ['https://www.googleapis.com/auth/firebase.messaging']
credentials = service_account.Credentials.from_service_account_file(SERVICE_ACCOUNT_FILE, scopes=SCOPES)

# Get the default session

request = google.auth.transport.requests.Request()

credentials.refresh(request)

# Get the access token

access_token = credentials.token

# Print the access token

print('Access Token: ' + access_token)