<?php

namespace LPTracker;

use LPTracker\authentication\AccessToken;
use LPTracker\exceptions\LPTrackerSDKException;
use LPTracker\models\Comment;
use LPTracker\models\Contact;
use LPTracker\models\ContactField;
use LPTracker\models\Custom;
use LPTracker\models\CustomField;
use LPTracker\models\Employee;
use LPTracker\models\Lead;
use LPTracker\models\LeadFile;
use LPTracker\models\Project;
use LPTracker\models\Stage;
use LPTracker\models\View;

class LPTracker extends LPTrackerBase
{
    /**
     * @param string $login
     * @param string $password
     * @param string $serviceName
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
        $response = LPTrackerRequest::sendRequest(
            '/login',
            [
                'login' => $login,
                'password' => $password,
                'service' => $serviceName,
                'version' => LPTrackerBase::VERSION,
            ],
            'POST',
            null,
            $this->address
        );
        return new AccessToken($response['token']);
    }

    /**
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
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
    public function getProjects()
    {
        $response = LPTrackerRequest::sendRequest('/projects', [], 'GET', $this->token, $this->address);
        $result = [];
        foreach ($response as $projectData) {
            $result[] = new Project($projectData);
        }
        return $result;
    }

    /**
     * @param Project|int $project
     * @return Project
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     */
    public function getProject($project)
    {
        if ($project instanceof Project) {
            $project = $project->getId();
        } else {
            $project = (int) $project;
        }
        $url = '/project/' . $project;
        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);
        return new Project($response);
    }

    /**
     * @return Project[]
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     * @deprecated Use getProjects()
     */
    public function getProjectList()
    {
        return $this->getProjects();
    }

    /**
     * @param Project|int $project
     * @return Custom[]
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     */
    public function getProjectCustoms($project)
    {
        if ($project instanceof Project) {
            $project = $project->getId();
        } else {
            $project = (int) $project;
        }
        $url = '/project/' . $project . '/customs';
        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);
        $result = [];
        foreach ($response as $customData) {
            $result[] = new Custom($customData);
        }
        return $result;
    }

    /**
     * @param Project|int $project
     * @return Stage[]
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     */
    public function getProjectStages($project)
    {
        if ($project instanceof Project) {
            $project = $project->getId();
        } else {
            $project = (int) $project;
        }
        $url = '/project/' . $project . '/funnel';
        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);
        $result = [];
        foreach ($response as $stageData) {
            $result[] = new Stage($stageData);
        }
        return $result;
    }

    /**
     * @param Project|int $project
     * @return ContactField[]
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     */
    public function getProjectFields($project)
    {
        if ($project instanceof Project) {
            $project = $project->getId();
        } else {
            $project = (int) $project;
        }
        $url = '/project/' . $project . '/fields';
        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);
        $result = [];
        foreach ($response as $customData) {
            $result[] = new ContactField($customData);
        }
        return $result;
    }

    /**
     * @param Project|int $project
     * @param string $callbackUrl
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     */
    public function setProjectCallbackUrl($project, $callbackUrl)
    {
        if ($project instanceof Project) {
            $project = $project->getId();
        } else {
            $project = (int) $project;
        }
        $url = '/project/' . $project . '/callback-url';
        LPTrackerRequest::sendRequest($url, ['url' => $callbackUrl], 'PUT', $this->token, $this->address);
    }

    /**
     * @return Employee[]
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     */
    public function getEmployees()
    {
        $response = LPTrackerRequest::sendRequest('/staff', [], 'GET', $this->token, $this->address);
        $result = [];
        foreach ($response as $employeeData) {
            $result[] = new Employee($employeeData);
        }
        return $result;
    }

    /**
     * @param Project|int $project
     * @param array $details
     * @param array $contactData
     * @param array $fields
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
            $project = (int) $project;
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
        return new Contact($response);
    }

    /**
     * @param Contact|int $contact
     * @return Contact
     * @throws LPTrackerSDKException
     */
    public function getContact($contact)
    {
        if ($contact instanceof Contact) {
            $contact = $contact->getId();
        } else {
            $contact = (int) $contact;
        }
        if ($contact <= 0) {
            throw new LPTrackerSDKException('Invalid contact ID');
        }

        $url = '/contact/' . $contact;
        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);
        return new Contact($response);
    }

    /**
     * @param Contact $contact
     * @return Contact
     * @throws LPTrackerSDKException
     */
    public function saveContact(Contact $contact)
    {
        if (!$contact->validate()) {
            throw new LPTrackerSDKException('Invalid contact');
        }

        $data = $contact->toArray();
        if (!empty($data['fields'])) {
            $fields = [];
            foreach ($data['fields'] as $field) {
                $fields[$field['id']] = $field['value'];
            }
            $data['fields'] = $fields;
        }
        if ($contact->getId() > 0) {
            $url = '/contact/' . $contact->getId();
            $response = LPTrackerRequest::sendRequest($url, $contact->toArray(), 'PUT', $this->token, $this->address);
        } else {
            $response = LPTrackerRequest::sendRequest('/contact', $contact->toArray(), 'POST', $this->token, $this->address);
        }
        return new Contact($response);
    }

    /**
     * @param Contact|int $contact
     * @param array $details
     * @param array $contactData
     * @param array $fields
     * @return Contact
     * @throws LPTrackerSDKException
     */
    public function editContact(
        $contact,
        array $details,
        array $contactData = [],
        array $fields = []
    ) {
        if ($contact instanceof Contact) {
            $contact = $contact->getId();
        } else {
            $contact = (int) $contact;
        }
        if (empty($details)) {
            throw new LPTrackerSDKException('Contact details can not be empty');
        }

        $contactData['id'] = $contact;
        $contactData['details'] = $details;
        foreach ($fields as $fieldId => $fieldValue) {
            if ($fieldValue instanceof ContactField) {
                $fieldId = $fieldValue->getId();
                $fieldValue = $fieldValue->getValue();
            }
            $contactData['fields'][] = [
                'id' => $fieldId,
                'value' => $fieldValue,
            ];
        }
        $contact = new Contact($contactData);
        $contact->validate();
        return $this->saveContact($contact);
    }

    /**
     * @param Project|int $project
     * @param array $searchOptions
     * @return Contact[]
     * @throws LPTrackerSDKException
     */
    public function searchContacts($project, array $searchOptions = [])
    {
        if ($project instanceof Project) {
            $project = $project->getId();
        } else {
            $project = (int) $project;
        }
        if ($project <= 0) {
            throw new LPTrackerSDKException('Invalid project id');
        }

        $data = [
            'project_id' => $project,
        ];
        if (isset($searchOptions['email'])) {
            $data['email'] = $searchOptions['email'];
        }
        if (isset($searchOptions['phone'])) {
            $data['phone'] = $searchOptions['phone'];
        }
        $url = '/contact/search?' . http_build_query($data);
        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);
        $result = [];
        foreach ($response as $contactData) {
            $result[] = new Contact($contactData);
        }
        return $result;
    }

    /**
     * @param Contact|int $contact
     * @return Lead[]
     * @throws LPTrackerSDKException
     */
    public function getContactLeads($contact)
    {
        if ($contact instanceof Contact) {
            $contact = $contact->getId();
        } else {
            $contact = (int) $contact;
        }
        if ($contact <= 0) {
            throw new LPTrackerSDKException('Invalid contact id');
        }

        $url = '/contact/' . $contact . '/leads';
        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);
        $result = [];
        foreach ($response as $leadData) {
            $result[] = new Lead($leadData);
        }
        return $result;
    }

    /**
     * @param Contact|int $contact
     * @return array
     * @throws LPTrackerSDKException
     * @deprecated Use getContactLeads()
     */
    public function contactLeads($contact)
    {
        return $this->getContactLeads($contact);
    }

    /**
     * @param Contact|int $contact
     * @param ContactField|int $field
     * @param string $newValue
     * @return ContactField
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     */
    public function updateContactField($contact, $field, $newValue)
    {
        if ($contact instanceof Contact) {
            $contact = $contact->getId();
        } else {
            $contact = (int) $contact;
        }
        if ($field instanceof ContactField) {
            $field = $field->getId();
        } else {
            $field = (int) $field;
        }
        $url = '/contact/' . $contact . '/field/' . $field;
        $data = [
            'value' => $newValue,
        ];
        $response = LPTrackerRequest::sendRequest($url, $data, 'PUT', $this->token, $this->address);
        return new ContactField($response);
    }

    /**
     * @param Project|int $project
     * @param array $viewData
     * @return View
     * @throws LPTrackerSDKException
     */
    public function createView($project, array $viewData = [])
    {
        if ($project instanceof Project) {
            $viewData['project_id'] = $project->getId();
        } else {
            $viewData['project_id'] = (int) $project;
        }
        $view = new View($viewData);
        if (!$view->validate()) {
            throw new LPTrackerSDKException('Invalid view data');
        }

        $data = $view->toArray();
        $response = LPTrackerRequest::sendRequest('/view', $data, 'POST', $this->token, $this->address);
        return new View($response);
    }

    /**
     * @param View|int $view
     * @return View
     * @throws LPTrackerSDKException
     */
    public function getView($view)
    {
        if ($view instanceof View) {
            $view = $view->getId();
        } else {
            $view = (int) $view;
        }
        if ($view <= 0) {
            throw new LPTrackerSDKException('Invalid view ID');
        }

        $url = '/view/' . $view;
        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);
        return new View($response);
    }

    /**
     * @param View $view
     * @return View
     * @throws LPTrackerSDKException
     */
    public function saveView(View $view)
    {
        if (!$view->validate()) {
            throw new LPTrackerSDKException('Invalid view');
        }

        if ($view->getId() > 0) {
            $url = '/view/' . $view->getId();
            $response = LPTrackerRequest::sendRequest($url, $view->toArray(), 'PUT', $this->token, $this->address);
        } else {
            $response = LPTrackerRequest::sendRequest('/view', $view->toArray(), 'POST', $this->token, $this->address);
        }
        return new View($response);
    }

    /**
     * @param View|int $view
     * @param array $viewData
     * @return View
     * @throws LPTrackerSDKException
     */
    public function editView($view, array $viewData = [])
    {
        if ($view instanceof View) {
            $viewData['id'] = $view->getId();
        } else {
            $viewData['id'] = (int) $view;
        }
        $view = new View($viewData);
        $view->validate();
        return $this->saveView($view);
    }

    /**
     * @param Contact|int $contact
     * @param array $leadData
     * @param array $options
     * @return Lead
     * @throws LPTrackerSDKException
     */
    public function createLead($contact, array $leadData = [], array $options = [])
    {
        if ($contact instanceof Contact) {
            $leadData['contact_id'] = $contact->getId();
        } else {
            $leadData['contact_id'] = (int) $contact;
        }
        $lead = new Lead($leadData);
        if (!$lead->validate()) {
            throw new LPTrackerSDKException('Invalid lead data');
        }

        if (!empty($lead->getView()) && empty($lead->getView()->getId())) {
            $contactModel = $this->getContact($contact);
            $viewData = $lead->getView()->toArray();
            $lead->setView($this->createView($contactModel->getProjectId(), $viewData));
        }
        $data = $lead->toArray(true);
        if (isset($options['callback'])) {
            $data['callback'] = $options['callback'] ? true : false;
        }
        if (isset($leadData['view_id'])) {
            $data['view_id'] = (int) $leadData['view_id'];
        } elseif (!empty($lead->getView()) && !empty($lead->getView()->getId())) {
            $data['view_id'] = $lead->getView()->getId();
        }
        $response = LPTrackerRequest::sendRequest('/lead', $data, 'POST', $this->token, $this->address);
        return new Lead($response);
    }

    /**
     * @param Lead|int $lead
     * @return Lead
     * @throws LPTrackerSDKException
     */
    public function getLead($lead)
    {
        if ($lead instanceof Lead) {
            $lead = $lead->getId();
        } else {
            $lead = (int) $lead;
        }
        if ($lead <= 0) {
            throw new LPTrackerSDKException('Invalid lead ID');
        }

        $url = '/lead/' . $lead;
        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);
        return new Lead($response);
    }

    /**
     * @param Lead|int $lead
     * @param Custom|int $custom
     * @return LeadFile
     * @throws LPTrackerSDKException
     */
    public function getCustomFile($lead, $custom, $file)
    {
        if ($lead instanceof Lead) {
            $lead = $lead->getId();
        } else {
            $lead = (int) $lead;
        }
        if ($custom instanceof Custom) {
            $custom = $custom->getId();
        } else {
            $custom = (int) $custom;
        }
        if ($lead <= 0) {
            throw new LPTrackerSDKException('Invalid lead ID');
        }
        if ($custom <= 0) {
            throw new LPTrackerSDKException('Invalid custom ID');
        }
        $file = (int)$file;
        if ($file <= 0) {
            throw new LPTrackerSDKException('Invalid file ID');
        }

        $url = '/lead/' . $lead . '/custom/' . $custom . '/file/' . $file;
        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);
        return new LeadFile($response);
    }

    /**
     * @param Lead $lead
     * @return Lead
     * @throws LPTrackerSDKException
     */
    public function saveLead(Lead $lead)
    {
        if (!$lead->validate()) {
            throw new LPTrackerSDKException('Invalid lead');
        }

        if ($lead->getId() > 0) {
            $url = '/lead/' . $lead->getId();
            $response = LPTrackerRequest::sendRequest($url, $lead->toArray(true), 'PUT', $this->token, $this->address);
        } else {
            $response = LPTrackerRequest::sendRequest(
                '/lead',
                $lead->toArray(true),
                'POST',
                $this->token,
                $this->address
            );
        }
        return new Lead($response);
    }

    /**
     * @param Lead|int $lead
     * @param array $leadData
     * @return Lead
     * @throws LPTrackerSDKException
     */
    public function editLead($lead, array $leadData = [])
    {
        if ($lead instanceof Lead) {
            $leadData['id'] = $lead->getId();
        } else {
            $leadData['id'] = (int) $lead;
        }
        $lead = new Lead($leadData);
        $lead->validate();
        return $this->saveLead($lead);
    }

    /**
     * @param Lead|int $lead
     * @param string $category
     * @param string $purpose
     * @param float $sum
     * @return Lead
     * @throws LPTrackerSDKException
     */
    public function addPaymentToLead($lead, $category, $purpose, $sum)
    {
        if ($lead instanceof Lead) {
            $lead = $lead->getId();
        } else {
            $lead = (int) $lead;
        }
        if (empty($category)) {
            throw new LPTrackerSDKException('Category can not be empty');
        }

        if (empty($purpose)) {
            throw new LPTrackerSDKException('Purpose can not be empty');
        }

        $sum = (float) $sum;
        if ($sum <= 0) {
            throw new LPTrackerSDKException('Invalid sum');
        }

        if ($lead <= 0) {
            throw new LPTrackerSDKException('Invalid lead ID');
        }

        $url = '/lead/' . $lead . '/payment';
        $data = [
            'category' => $category,
            'purpose' => $purpose,
            'sum' => $sum,
        ];
        $response = LPTrackerRequest::sendRequest($url, $data, 'POST', $this->token, $this->address);
        return new Lead($response);
    }

    /**
     * @param Lead|int $lead
     * @param string $category
     * @param string $purpose
     * @param float $sum
     * @return Lead
     * @throws LPTrackerSDKException
     * @deprecated Use addPaymentToLead()
     */
    public function addLeadPayment($lead, $category, $purpose, $sum)
    {
        return $this->addPaymentToLead($lead, $category, $purpose, $sum);
    }

    /**
     * @param Lead|int $lead
     * @param int $newStageId
     * @param array $options
     * @return Lead
     * @throws LPTrackerSDKException
     */
    public function editLeadStage($lead, $newStageId, array $options = [])
    {
        if ($lead instanceof Lead) {
            $lead = $lead->getId();
        } else {
            $lead = (int) $lead;
        }
        $url = '/lead/' . $lead . '/funnel';
        $data = [
            'funnel' => $newStageId,
            'options' => $options,
        ];
        $response = LPTrackerRequest::sendRequest($url, $data, 'PUT', $this->token, $this->address);
        return new Lead($response);
    }

    /**
     * @param Lead|int $lead
     * @param int $newFunnelId
     * @return Lead
     * @throws LPTrackerSDKException
     * @deprecated Use editLeadStage()
     */
    public function changeLeadFunnel($lead, $newFunnelId)
    {
        return $this->editLeadStage($lead, $newFunnelId);
    }

    /**
     * @param Lead|int $lead
     * @param int $newOwnerId
     * @param array $options
     * @return Lead
     * @throws LPTrackerSDKException
     */
    public function editLeadOwner($lead, $newOwnerId, array $options = [])
    {
        if ($lead instanceof Lead) {
            $lead = $lead->getId();
        } else {
            $lead = (int) $lead;
        }
        $url = '/lead/' . $lead . '/owner';
        $data = [
            'owner' => $newOwnerId,
            'options' => $options,
        ];
        $response = LPTrackerRequest::sendRequest($url, $data, 'PUT', $this->token, $this->address);
        return new Lead($response);
    }

    /**
     * @param Lead|int $lead
     * @return Comment[]
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     */
    public function getLeadComments($lead)
    {
        if ($lead instanceof Lead) {
            $lead = $lead->getId();
        } else {
            $lead = (int) $lead;
        }
        $url = '/lead/' . $lead . '/comments';
        $response = LPTrackerRequest::sendRequest($url, [], 'GET', $this->token, $this->address);
        $result = [];
        foreach ($response as $commentData) {
            $result[] = new Comment($commentData);
        }
        return $result;
    }

    /**
     * @param Lead|int $lead
     * @param string $text
     * @param array $options
     * @return Comment
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     */
    public function addCommentToLead($lead, $text, array $options = [])
    {
        if ($lead instanceof Lead) {
            $lead = $lead->getId();
        } else {
            $lead = (int) $lead;
        }
        $url = '/lead/' . $lead . '/comment';
        $data = [
            'text' => $text,
            'options' => $options,
        ];
        $response = LPTrackerRequest::sendRequest($url, $data, 'POST', $this->token, $this->address);
        return new Comment($response);
    }

    /**
     * @param Lead|int $lead
     * @param Custom|int $custom
     * @param string $absolutePath
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     */
    public function addFileToLead($lead, $custom, $absolutePath)
    {
        if ($lead instanceof Lead) {
            $lead = $lead->getId();
        } else {
            $lead = (int) $lead;
        }
        if ($custom instanceof Custom) {
            $custom = $custom->getId();
        } else {
            $custom = (int) $custom;
        }
        $url = '/lead/' . $lead . '/file';
        $data = [
            'name' => pathinfo($absolutePath, PATHINFO_BASENAME),
            'mime' => mime_content_type($absolutePath),
            'data' => base64_encode(file_get_contents($absolutePath)),
            'custom_field_id' => $custom,
        ];
        LPTrackerRequest::sendRequest($url, $data, 'POST', $this->token, $this->address);
    }

    /**
     * @param Custom $custom
     * @return Custom
     * @throws LPTrackerSDKException
     */
    public function saveLeadCustom(Custom $custom, array $options = [])
    {
        if (!$custom->validate() || empty($custom->getLeadId())) {
            throw new LPTrackerSDKException('Invalid custom');
        }

        $url = '/lead/' . $custom->getLeadId() . '/custom/' . $custom->getId();
        if ($custom->getValue() === null) {
            $data = [
                'options' => $options,
            ];
            $response = LPTrackerRequest::sendRequest($url, $data, 'DELETE', $this->token, $this->address);
        } else {
            $data = [
                'value' => $custom->getValue(),
                'options' => $options,
            ];
            $response = LPTrackerRequest::sendRequest($url, $data, 'PUT', $this->token, $this->address);
        }
        return new Custom($response, $custom->getLeadId());
    }

    /**
     * @param Lead|int $lead
     * @param Custom|int $custom
     * @param mixed $newValue
     * @param array $options
     * @return Custom
     * @throws LPTrackerSDKException
     */
    public function editLeadCustom($lead, $custom, $newValue, array $options = [])
    {
        if ($lead instanceof Lead) {
            $lead = $lead->getId();
        } else {
            $lead = (int) $lead;
        }
        if ($custom instanceof Custom) {
            if (empty($newValue)) {
                $newValue = $custom->getValue();
            }
            $custom = $custom->getId();
        } else {
            $custom = (int) $custom;
        }
        $customModel = new Custom([
            'id' => $custom,
            'value' => $newValue,
        ], $lead);
        $customModel->validate();
        return $this->saveLeadCustom($customModel, $options);
    }

    /**
     * @param Project|int $project
     * @param array $options
     * @return CustomField
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     */
    public function createCustom($project, $options)
    {
        if ($project instanceof Project) {
            $project = $project->getId();
        } else {
            $project = (int) $project;
        }
        $actionUrl = '/custom/' . $project . '/create';
        $response = LPTrackerRequest::sendRequest($actionUrl, $options, 'POST', $this->token, $this->address);
        return new CustomField($response);
    }

    /**
     * @param Project|int $project
     * @param int $offset
     * @param int $limit
     * @param array $sort
     * @param bool $isDeal
     * @param array $filter
     * @return Lead[]
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     */
    public function getLeads($project, $offset = null, $limit = null, $sort = [], $isDeal = false, $filter = [])
    {
        if ($project instanceof Project) {
            $project = $project->getId();
        } else {
            $project = (int) $project;
        }
        $actionUrl = '/lead/' . $project . '/list?' . http_build_query([
            'offset' => $offset,
            'limit' => $limit,
            'sort' => $sort,
            'is_deal' => $isDeal,
            'filter' => $filter,
        ]);
        $response = LPTrackerRequest::sendRequest($actionUrl, [], 'GET', $this->token, $this->address);
        $result = [];
        foreach ($response as $lead) {
            $result[] = new Lead($lead);
        }
        return $result;
    }

    /**
     * @param Project|int $project
     * @param int $offset
     * @param int $limit
     * @param array $sort
     * @param bool $isDeal
     * @param array $filter
     * @return Lead[]
     * @throws exceptions\LPTrackerResponseException
     * @throws exceptions\LPTrackerServerException
     * @deprecated Use getLeads()
     */
    public function getLeadsList($project, $offset = null, $limit = null, $sort = [], $isDeal = false, $filter = [])
    {
        return $this->getLeads($project, $offset, $limit, $sort, $isDeal, $filter);
    }
}
