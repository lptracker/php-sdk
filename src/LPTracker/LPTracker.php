<?php

namespace LPTracker;

use LPTracker\models\Comment;
use LPTracker\models\Contact;
use LPTracker\models\ContactField;
use LPTracker\models\Custom;
use LPTracker\models\Lead;
use LPTracker\models\Project;
use LPTracker\exceptions\LPTrackerSDKException;
use LPTracker\authentication\AccessToken;
use LPTracker\models\View;

/**
 * Class LPTracker
 * @package LPTracker
 */
class LPTracker extends LPTrackerBase
{

    /**
     * @param        $login
     * @param        $password
     * @param string $serviceName
     *
     * @return AccessToken
     * @throws LPTrackerSDKException
     */
    public function login($login, $password, $serviceName = '')
    {
        if (empty($login)) {
            throw new LPTrackerSDKException('Login is empty');
        }
        if (empty($password)) {
            throw new LPTrackerSDKException('Password is empty');
        }
        if (empty($serviceName)) {
            $serviceName = LPTrackerBase::DEFAULT_SERVICE_NAME;
        }

        $response = LPTrackerRequest::sendRequest('/login', [
            'login'    => $login,
            'password' => $password,
            'service'  => $serviceName,
            'version'  => LPTrackerBase::VERSION
        ], 'POST', null, $this->address);

        $accessToken = new AccessToken($response['token']);

        return $accessToken;
    }


    /**
     *
     */
    public function logout()
    {
        LPTrackerRequest::sendRequest('/logout', [], 'POST', $this->token, $this->address);
    }


    /**
     * @return Project[]
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     */
    public function getProjectList()
    {
        $response = LPTrackerRequest::sendRequest('/projects', [], 'GET', $this->token, $this->address);

        $result = [];
        foreach ($response as $projectData) {
            $result[] = new Project($projectData);
        }

        return $result;
    }


    /**
     * @param $id
     *
     * @return Project
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     */
    public function getProject($id)
    {
        $url = '/project/'.$id;

        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);

        $project = new Project($response);

        return $project;
    }


    /**
     * @param $project
     *
     * @return Custom[]
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     */
    public function getProjectCustoms($project)
    {
        if ($project instanceof Project) {
            $project = $project->getId();
        } else {
            $project = intval($project);
        }

        $url = '/project/'.$project.'/customs';

        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);

        $result = [];
        foreach ($response as $customData) {
            $result[] = new Custom($customData);
        }

        return $result;
    }


    /**
     * @param $project
     *
     * @return ContactField[]
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     */
    public function getProjectFields($project)
    {
        if ($project instanceof Project) {
            $project = $project->getId();
        } else {
            $project = intval($project);
        }

        $url = '/project/'.$project.'/fields';

        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);

        $result = [];
        foreach ($response as $customData) {
            $result[] = new ContactField($customData);
        }

        return $result;
    }


    /**
     * @param       $project
     * @param array $details
     * @param array $contactData
     * @param array $fields
     *
     * @return Contact
     * @throws LPTrackerSDKException
     */
    public function createContact(
        $project,
        array $details,
        array $contactData = [],
        array $fields = []
    ) {
        if (empty($details)) {
            throw new LPTrackerSDKException('Contact details can not be empty');
        }

        if ($project instanceof Project) {
            $project = $project->getId();
        } else {
            $project = intval($project);
        }

        $contactData['project_id'] = $project;
        $contactData['details'] = $details;

        $fieldsArr = [];
        foreach ($fields as $fieldId => $fieldValue) {
            if ($fieldValue instanceof ContactField) {
                $fieldId = $fieldValue->getId();
                $fieldValue = $fieldValue->getValue();
            }

            $fieldsArr[$fieldId] = $fieldValue;
        }

        $contact = new Contact($contactData);
        $contact->validate();

        $data = $contact->toArray();
        $data['fields'] = $fieldsArr;

        $response = LPTrackerRequest::sendRequest('/contact', $data, 'POST', $this->token, $this->address);

        $resultContact = new Contact($response);

        return $resultContact;
    }


    /**
     * @param $contact
     *
     * @return Contact
     * @throws LPTrackerSDKException
     */
    public function getContact($contact)
    {
        if ($contact instanceof Contact) {
            $contact = $contact->getId();
        } else {
            $contact = intval($contact);
        }

        if ($contact <= 0) {
            throw new LPTrackerSDKException('Invalid contact ID');
        }

        $url = '/contact/'.$contact;

        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);

        $resultContact = new Contact($response);

        return $resultContact;
    }


    /**
     * @param Contact $contact
     *
     * @return Contact
     * @throws LPTrackerSDKException
     */
    public function saveContact(Contact $contact)
    {
        if ( ! $contact->validate()) {
            throw new LPTrackerSDKException('Invalid contact');
        }

        $data = $contact->toArray();
        if ( ! empty($data['fields'])) {
            $fields = [];
            foreach ($data['fields'] as $field) {
                $fields[$field['id']] = $field['value'];
            }
            $data['fields'] = $fields;
        }

        if ($contact->getId() > 0) {
            $url = '/contact/'.$contact->getId();

            $response = LPTrackerRequest::sendRequest($url, $contact->toArray(), 'PUT', $this->token, $this->address);
        } else {
            $response = LPTrackerRequest::sendRequest('/contact', $contact->toArray(), 'POST', $this->token,
                $this->address);
        }

        $resultContact = new Contact($response);

        return $resultContact;
    }


    /**
     * @param       $contactId
     * @param array $details
     * @param array $contactData
     * @param array $fields
     *
     * @return Contact
     * @throws LPTrackerSDKException
     */
    public function editContact(
        $contactId,
        array $details,
        array $contactData = [],
        array $fields = []
    ) {
        if (empty($details)) {
            throw new LPTrackerSDKException('Contact details can not be empty');
        }

        $contactData['id'] = $contactId;
        $contactData['details'] = $details;

        foreach ($fields as $fieldId => $fieldValue) {
            if ($fieldValue instanceof ContactField) {
                $fieldId = $fieldValue->getId();
                $fieldValue = $fieldValue->getValue();
            }

            $contactData['fields'][] = [
                'id'    => $fieldId,
                'value' => $fieldValue
            ];
        }

        $contact = new Contact($contactData);
        $contact->validate();

        return $this->saveContact($contact);
    }


    /**
     * @param       $project
     * @param array $searchOptions
     *
     * @return array
     * @throws LPTrackerSDKException
     */
    public function searchContacts($project, array $searchOptions = [])
    {
        if ($project instanceof Project) {
            $project = $project->getId();
        }
        $project = intval($project);
        if ($project <= 0) {
            throw new LPTrackerSDKException('Invalid project id');
        }

        $url = '/contact/search?';

        $data = [
            'project_id' => $project
        ];
        if (isset($searchOptions['email'])) {
            $data['email'] = $searchOptions['email'];
        }
        if (isset($searchOptions['phone'])) {
            $data['phone'] = $searchOptions['phone'];
        }

        $url = $url.http_build_query($data);

        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);

        $result = [];
        foreach ($response as $contactData) {
            $result[] = new Contact($contactData);
        }

        return $result;
    }


    /**
     * @param $contact
     *
     * @return array
     * @throws LPTrackerSDKException
     */
    public function contactLeads($contact)
    {
        if ($contact instanceof Contact) {
            $contact = $contact->getId();
        }
        $contact = intval($contact);
        if ($contact <= 0) {
            throw new LPTrackerSDKException('Invalid contact id');
        }

        $url = '/contact/'.$contact.'/leads';

        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);

        $result = [];
        foreach ($response as $leadData) {
            $result[] = new Lead($leadData);
        }

        return $result;
    }


    /**
     * @param $contact
     * @param $field
     * @param $newValue
     *
     * @return ContactField
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     */
    public function updateContactField($contact, $field, $newValue)
    {
        if ($contact instanceof Contact) {
            $contact = $contact->getId();
        }
        if ($field instanceof ContactField) {
            $field = $field->getId();
        }

        $url = '/contact/'.$contact.'/field/'.$field;

        $data = [
            'value' => $newValue
        ];

        $response = LPTrackerRequest::sendRequest($url, $data, 'PUT', $this->token, $this->address);

        $contactField = new ContactField($response);

        return $contactField;
    }


    /**
     * @param       $project
     * @param array $viewData
     *
     * @return View
     * @throws LPTrackerSDKException
     */
    public function createView($project, array $viewData = [])
    {
        if ($project instanceof Project) {
            $viewData['project_id'] = $project->getId();
        } else {
            $viewData['project_id'] = intval($project);
        }

        $view = new View($viewData);
        if ( ! $view->validate()) {
            throw new LPTrackerSDKException('Invalid view data');
        }

        $data = $view->toArray();

        $response = LPTrackerRequest::sendRequest('/view', $data, 'POST', $this->token, $this->address);

        $resultView = new View($response);

        return $resultView;
    }


    /**
     * @param $view
     *
     * @return View
     * @throws LPTrackerSDKException
     */
    public function getView($view)
    {
        if ($view instanceof View) {
            $view = $view->getId();
        } else {
            $view = intval($view);
        }

        if ($view <= 0) {
            throw new LPTrackerSDKException('Invalid view ID');
        }

        $url = '/view/'.$view;

        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);

        $resultView = new View($response);

        return $resultView;
    }


    /**
     * @param View $view
     *
     * @return View
     * @throws LPTrackerSDKException
     */
    public function saveView(View $view)
    {
        if ( ! $view->validate()) {
            throw new LPTrackerSDKException('Invalid view');
        }

        if ($view->getId() > 0) {
            $url = '/view/'.$view->getId();

            $response = LPTrackerRequest::sendRequest($url, $view->toArray(), 'PUT', $this->token, $this->address);
        } else {
            $response = LPTrackerRequest::sendRequest('/view', $view->toArray(), 'POST', $this->token, $this->address);
        }

        $resultView = new View($response);

        return $resultView;
    }


    /**
     * @param       $viewId
     * @param array $viewData
     *
     * @return View
     * @throws LPTrackerSDKException
     */
    public function editView($viewId, array $viewData = [])
    {
        $viewData['id'] = $viewId;

        $view = new View($viewData);
        $view->validate();

        return $this->saveView($view);
    }


    /**
     * @param       $contact
     * @param array $leadData
     * @param array $options
     *
     * @return Lead
     * @throws LPTrackerSDKException
     */
    public function createLead($contact, array $leadData = [], array $options = [])
    {
        if ($contact instanceof Contact) {
            $leadData['contact_id'] = $contact->getId();
        } else {
            $leadData['contact_id'] = intval($contact);
        }

        $lead = new Lead($leadData);
        if ( ! $lead->validate()) {
            throw new LPTrackerSDKException('Invalid lead data');
        }

        $data = $lead->toArray(true);
        if (isset($options['callback'])) {
            $data['callback'] = $options['callback'] ? true : false;
        }
        if (isset($leadData['view_id'])) {
            $data['view_id'] = intval($leadData['view_id']) ;
        }

        $response = LPTrackerRequest::sendRequest('/lead', $data, 'POST', $this->token, $this->address);

        $resultLead = new Lead($response);

        return $resultLead;
    }


    /**
     * @param $lead
     *
     * @return Lead
     * @throws LPTrackerSDKException
     */
    public function getLead($lead)
    {
        if ($lead instanceof Lead) {
            $lead = $lead->getId();
        } else {
            $lead = intval($lead);
        }

        if ($lead <= 0) {
            throw new LPTrackerSDKException('Invalid lead ID');
        }

        $url = '/lead/'.$lead;

        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);

        $resultLead = new Lead($response);

        return $resultLead;
    }


    /**
     * @param Lead $lead
     *
     * @return Lead
     * @throws LPTrackerSDKException
     */
    public function saveLead(Lead $lead)
    {
        if ( ! $lead->validate()) {
            throw new LPTrackerSDKException('Invalid lead');
        }

        if ($lead->getId() > 0) {
            $url = '/lead/'.$lead->getId();

            $response = LPTrackerRequest::sendRequest($url, $lead->toArray(true), 'PUT', $this->token, $this->address);
        } else {
            $response = LPTrackerRequest::sendRequest('/lead', $lead->toArray(true), 'POST', $this->token, $this->address);
        }

        $resultLead = new Lead($response);

        return $resultLead;
    }


    /**
     * @param       $leadId
     * @param array $leadData
     *
     * @return Lead
     * @throws LPTrackerSDKException
     */
    public function editLead($leadId, array $leadData = [])
    {
        $leadData['id'] = $leadId;

        $lead = new Lead($leadData);
        $lead->validate();

        return $this->saveLead($lead);
    }


    /**
     * @param        $lead
     * @param string $category
     * @param string $purpose
     * @param float  $sum
     *
     * @return Lead
     * @throws LPTrackerSDKException
     */
    public function addLeadPayment($lead, $category, $purpose, $sum)
    {
        if ($lead instanceof Lead) {
            $lead = $lead->getId();
        } else {
            $lead = intval($lead);
        }

        if (empty($category)) {
            throw new LPTrackerSDKException('Category can not be empty');
        }
        if (empty($purpose)) {
            throw new LPTrackerSDKException('Purpose can not be empty');
        }
        $sum = floatval($sum);

        if ($sum <= 0) {
            throw new LPTrackerSDKException('Invalid sum');
        }

        if ($lead <= 0) {
            throw new LPTrackerSDKException('Invalid lead ID');
        }

        $url = '/lead/'.$lead.'/payment';

        $data = [
            'category' => $category,
            'purpose'  => $purpose,
            'sum'      => $sum,
        ];

        $response = LPTrackerRequest::sendRequest($url, $data, 'POST', $this->token, $this->address);

        $resultLead = new Lead($response);

        return $resultLead;
    }

    /**
     * @param $lead
     * @param $newFunnelId
     * @return Lead
     * @throws LPTrackerSDKException
     */
    public function changeLeadFunnel($lead, $newFunnelId)
    {
        if ($lead instanceof Lead) {
            $lead = $lead->getId();
        }

        $url = '/lead/'.$lead.'/funnel';

        $data = [
            'funnel' => $newFunnelId
        ];

        $response = LPTrackerRequest::sendRequest($url, $data, 'PUT', $this->token, $this->address);

        $resultLead = new Lead($response);

        return $resultLead;
    }

    /**
     * @param $lead
     *
     * @return Comment[]
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     */
    public function getLeadComments($lead)
    {
        if ($lead instanceof Lead) {
            $lead = $lead->getId();
        } else {
            $lead = intval($lead);
        }

        $url = '/lead/'.$lead.'/comments';

        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);

        $result = [];
        foreach ($response as $commentData) {
            $result[] = new Comment($commentData);
        }

        return $result;
    }


    /**
     * @param $lead
     * @param $text
     *
     * @return Comment
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     */
    public function addCommentToLead($lead, $text)
    {
        if ($lead instanceof Lead) {
            $lead = $lead->getId();
        } else {
            $lead = intval($lead);
        }

        $url = '/lead/'.$lead.'/comment';

        $data = [
            'text' => $text
        ];

        $response = LPTrackerRequest::sendRequest($url, $data, 'POST', $this->token, $this->address);

        $comment = new Comment($response);

        return $comment;
    }


    /**
     * @param Custom $custom
     *
     * @return Custom
     * @throws LPTrackerSDKException
     */
    public function saveLeadCustom(Custom $custom)
    {
        if ( ! $custom->validate() || empty($custom->getLeadId())) {
            throw new LPTrackerSDKException('Invalid custom');
        }

        $url = '/lead/'.$custom->getLeadId().'/custom/'.$custom->getId();

        $data = [
            'value' => $custom->getValue()
        ];

        $response = LPTrackerRequest::sendRequest($url, $data, 'PUT', $this->token, $this->address);

        $resultCustom = new Custom($response);

        return $resultCustom;
    }


    /**
     * @param $lead
     * @param $custom
     * @param $newValue
     *
     * @return Custom
     * @throws LPTrackerSDKException
     */
    public function editLeadCustom($lead, $custom, $newValue)
    {
        if ($lead instanceof Lead) {
            $lead = $lead->getId();
        } else {
            $lead = intval($lead);
        }

        if ($custom instanceof Custom) {
            if (empty($newValue)) {
                $newValue = $custom->getValue();
            }
            $custom = $custom->getId();
        } else {
            $custom = intval($custom);
        }

        $customModel = new Custom([
            'id'      => $custom,
            'lead_id' => $lead,
            'value'   => $newValue
        ]);
        $customModel->validate();

        return $this->saveLeadCustom($customModel);
    }
}