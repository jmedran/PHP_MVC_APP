<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Projects extends REST_Controller{


  function __construct()
  {
      // Construct the parent class
      parent::__construct();

      // Configure limits on our controller methods
      // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
      $this->methods['projects_get']['limit'] = 500; // 500 requests per hour per user/key
      $this->methods['test']['limit'] = 500;
  }


  public function projects_get()
  {



      $projects = $this->project_model->get_projects();

      $id = $this->get('id');

      $search = $this->get('search');




      if ($search)
      {

            // stuff goes here if search was passed from the URL

            $data['projects'] = $this->project_model->search_projects2($search);

            foreach($data as $key => $value ){



              $data = $value;

            }

            $this->response($data, REST_Controller::HTTP_OK);

      }


      // If the id parameter doesn't exist return all the projects

      if ($id === NULL)
      {
          // Check if the projects data store contains projects (in case the database result returns NULL)
          if ($projects)
          {
              // Set the response and exit
              $this->response($projects, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
          }
          else
          {
              // Set the response and exit
              $this->response([
                  'status' => FALSE,
                  'message' => 'No projects were found'
              ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
          }
      }

      // Find and return a single record for a particular project.

      $id = (int) $id;

      // Validate the id.
      if ($id <= 0 )
      {
          // Invalid id, set the response and exit.
          $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
      }

      // Get the project from the array, using the id as key for retrieval.
      // Point to get_project() model

      $projects= $this->project_model->get_project($id);

      if (!empty($projects))
      {
          foreach ($projects as $key => $value)
          {
              if (isset($value['id']) && $value['id'] === $id)
              {
                  $projects = $value;
              }
          }
      }

      if (!empty($projects))
      {
          $this->set_response($projects, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
      }
      else
      {
          $this->set_response([
              'status' => FALSE,
              'message' => 'Project could not be found'
          ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
      }




      // If the id parameter doesn't exist return all the projects


}


public function test()
    {
        $data['projects'] = $this->project_model->search_projects();
        var_dump($data['projects']);
    }



    public function test2()
    {
        $data['projects'] = $this->project_model->search_projects2($search);
        var_dump($data['projects']);
    }



}// end projects



 ?>
