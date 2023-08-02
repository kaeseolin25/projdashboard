<?php
defined ('BASEPATH') OR exit('No direct script access allowed');

class Backend_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['form_validation']);
        $this->load->library('session');
        $this->load->model('Users_model');
    }
    
    
    public function index ()
    {

        if(isset($_SESSION['user'])){
            redirect(base_url('index.php/dashboard'));
        }

        if (isset($_POST['login_btn'])) {
            $email= $this->input->post('user_email');
            $pw= $this->input->post('user_password');

            $user_data=$this->Users_model->authenticate($email,$pw);

            if($user_data!==0){

                $user_info = [
                    'user_id'=>$user_data[0]->Id,
                    'firstname'=>$user_data[0]->Firstname,
                ];

                $this->session->set_userdata('user',$user_info);
                redirect('dashboard');

            }else{

                $this->session->set_flashdata('msg_login','Invalid Password. Please try again.');
            }
    
        }                  

        $this->load->view('backend/page/login');
    }
   

    public function index2() {
        $this->load->model('Users_model');
        $data['residents'] = $this->Users_model->getResidents();
        $this->load->view('edit_blotter', $data);
    }
    

    

    public function action()
{
    // Process the form data
    $name = $this->input->post('name');
    $email = $this->input->post('email');

    // Perform necessary actions with the form data

    // Redirect or display a success message
}
    public function register()
    {
        $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('repeatpass', 'Confirm Password', 'trim|required|matches[password]');
        $this->form_validation->set_error_delimiters('<p style="color:red;">', '</p>');
    
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('backend/page/register');
        }else {
            $admin_data = [
                'firstname' => $this->input->post('firstname', TRUE),
                'lastname' => $this->input->post('lastname', TRUE),
                'email' => $this->input->post('email', TRUE),
                'password' => $this->input->post('password', TRUE),
                'repeatpass' => $this->input->post('repeatpass', TRUE),
            ];
    
        
            $insert = $this->db->insert('admin', $admin_data);

            if ($insert) {
               /* echo $jsCode;*/
                $this->load->view('backend/page/login');
            }
        }
    }

    public function dashboard ()
    { 
        if(!isset($_SESSION['user'])){

            $this->session->set_flashdata('msg_login','Please Login');
            redirect(base_url('index.php/admin'));
        }

      
        {
            $this->load->model('Users_model');
            $data['residentCount'] = $this->Users_model->getResidentCount();
            $data['blotterCount'] = $this->Users_model->getBlotterCount();
            $data['male_count'] = $this->Users_model->count_male_residents();
            $data['female_count'] = $this->Users_model->count_female_residents();
            $data['senior_count'] = $this->Users_model->count_senior_residents();
            $data['resident_list'] = $this->Users_model->getResidents();
            $data['brgyofficial_list'] = $this->Users_model->get_all_officials();
            $data['blotter_list'] = $this->Users_model->getBlotter();
            
            
         
        
        $this->load->view('backend/include/header');
        $this->load->view('backend/include/nav');
        $this->load->view('backend/page/dashboard', $data);
        $this->load->view('backend/include/footer');
        }
        
     
    }
   
    public function logout()
{
    $this->session->unset_userdata('user'); // Assuming 'logged_in' is the session variable that indicates a user is logged in
    redirect('admin'); // Redirect to the login page or any other desired page
}
    public function add_resident(){
        
       
{
    if (!isset($_SESSION['user'])) {
        $this->session->set_flashdata('msg_login', 'You are not logged in. Please Login First');
        redirect(base_url('index.php/admin'));
    }

    $this->form_validation->set_rules('image','Image','validate_image_upload');
    $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('middle_name', 'Middle Name', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('name_ex', 'Name Extension', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('sex', 'Sex', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('birth_date', 'Birth Date', 'trim|required');
    $this->form_validation->set_rules('birth_place', 'Birth Place', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('contact', 'Contact', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('purok', 'Purok', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('barangay', 'Barangay', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('occupation', 'Occupation', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('civil_status', 'Civil Status', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('religion', 'Religion', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('nationality', 'Nationality', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('educational','Educational','trim|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('monthly_income','Monthly Income','trim|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('tot_housemember','Total Household Member','trim|required|min_length[2]|max_length[100]');
    $this->form_validation->set_rules('land_own','Land Ownership','trim|required|min_length[2]|max_length[100]');
    $this->form_validation->set_rules('house_own','House Ownership','trim|required|min_length[2]|max_length[100]');
    $this->form_validation->set_rules('diff_abled','Differently-Abled','trim|required|min_length[2]|max_length[100]');
    

    $this->form_validation->set_error_delimiters('<p style="color:red;">', '<p>');

    if ($this->form_validation->run() == FALSE) {
        $this->load->view('backend/include/header');
        $this->load->view('backend/include/nav');
        $this->load->view('backend/page/add_resident');
        $this->load->view('backend/include/footer');
    } else {

        $config['upload_path'] = './uploads/'; // Specify the path where the image will be uploaded
        $config['allowed_types'] = 'gif|jpg|jpeg|png'; // Allowed image file types
        $config['max_size'] = 2048; // Maximum file size in kilobytes (2MB)

        $this->load->library('upload', $config);

        
        if($_FILES['image']['name']==''){

            $this->session->set_flashdata('error','Please select an image');

            //  redirect(base_url('demo'));	
        }

        if(!$this->upload->do_upload('image')){
    
            $this->session->set_flashdata('error',$this->upload->display_errors());
 
               //redirect(base_url('demo'));
         }
         else{

            $this->session->set_flashdata('success','Image successfully uploaded');

            $image_data = $this->upload->data();
           // $image_path = 'uploads/'. $image_data['file_name'];
          $image_path ='./uploads/'. $image_data['file_name'];


        $resident_data = [
            'image' => $image_path,
            'first_name' => $this->input->post('first_name', TRUE),
            'middle_name' => $this->input->post('middle_name', TRUE),
            'last_name' => $this->input->post('last_name', TRUE),
            'name_ex' => $this->input->post('name_ex', TRUE),
            'sex' => $this->input->post('sex', TRUE),
            'birth_date' => $this->input->post('birth_date', TRUE),
            'birth_place' => $this->input->post('birth_place', TRUE),
            'contact' => $this->input->post('contact', TRUE),
            'email' => $this->input->post('email', TRUE),
            'purok' => $this->input->post('purok', TRUE),
            'barangay' => $this->input->post('barangay', TRUE),
            'occupation' => $this->input->post('occupation', TRUE),
            'civil_status' => $this->input->post('civil_status', TRUE),
            'religion' => $this->input->post('religion', TRUE),
            'nationality' => $this->input->post('nationality', TRUE),
            'educational' => $this->input->post('educational', TRUE),
            'monthly_income' => $this->input->post('monthly_income', TRUE),
            'tot_housemember' => $this->input->post('tot_housemember', TRUE),
            'land_own' => $this->input->post('land_own', TRUE),
            'house_own' => $this->input->post('house_own', TRUE),
            'diff_abled' => $this->input->post('diff_abled', TRUE),
           
         
        ];

        // Upload the image file if it exists
        if (isset($_FILES['image_file']) && $_FILES['image_file']['name'] !== '') {
            $config['upload_path'] = './uploads/'; // Set the upload path for the images
            $config['allowed_types'] = 'gif|jpg|jpeg|png'; // Allowed image types
            $config['max_size'] = 2048; // Maximum file size in kilobytes (2MB)

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('image_file')) {
                $upload_error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $upload_error);
                redirect(base_url('index.php/dashboard/add-resident'));
            } else {
                $upload_data = $this->upload->data();
                $resident_data['image'] = $upload_data['file_name']; // Set the 'image' field with the uploaded image file name
            }
        }

        $insert = $this->db->insert('resident', $resident_data);

        if ($insert) {
            $this->session->set_flashdata('success', 'Successfully Added!');
            redirect(base_url('index.php/dashboard/view-residents'));
        } else {
            $this->session->set_flashdata('error', 'Added Failed!');
            // Handle the case when insertion fails
        }
    }
}
    }
    }
    public function validate_image_upload()
    {
        if (!empty($_FILES['image']['name'])) {
            return true;
        } else {
            $this->form_validation->set_message('validate_image_upload', 'Please select an image to upload.');
            return false;
        }
    }
    public function search()
{
    $search_query = $this->input->get('search_query');
    // Perform search query using the provided input
    
    // Pass the search results to your view
    $data['search_results'] = $search_results;
    $this->load->view('index.php/dashboard/view-residents', $data);
}
	

    public function edit_resident($resident_id) {
        if (!isset($_SESSION['user'])) {
            $this->session->set_flashdata('msg_login', 'You are not logged in. Please Login First');
            redirect(base_url('index.php/admin'));
        }
    // Validate the input if necessary
    
    $this->form_validation->set_rules('image','Image','validate_image_upload');
    $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('middle_name', 'Middle Name', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('name_ex', 'Name Extension', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('sex', 'Sex', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('birth_date', 'Birth Date', 'trim|required');
    $this->form_validation->set_rules('birth_place', 'Birth Place', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('contact', 'Contact', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('purok', 'Purok', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('barangay', 'Barangay', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('occupation', 'Occupation', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('civil_status', 'Civil Status', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('religion', 'Religion', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('nationality', 'Nationality', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('educational','Educational','trim|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('monthly_income','Monthly Income','trim|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('tot_housemember','Total Household Member','trim|required|min_length[2]|max_length[100]');
    $this->form_validation->set_rules('land_own','Land Ownership','trim|required|min_length[2]|max_length[100]');
    $this->form_validation->set_rules('house_own','House Ownership','trim|required|min_length[2]|max_length[100]');
    $this->form_validation->set_rules('diff_abled','Differently-Abled','trim|required|min_length[2]|max_length[100]');
    
    $this->form_validation->set_error_delimiters('<p style="color:red;">', '<p>');
    
  
      
    
    if ($this->form_validation->run() == FALSE) {
        
        $resident_data = $this->db->get_where('resident', array('resident_id' => $resident_id))->row();
    
        $data = [
            'resident_data' => $resident_data
        ];

          
        $this->load->view('backend/include/header');
        $this->load->view('backend/include/nav');
        $this->load->view('backend/page/edit_resident', $data);
        $this->load->view('backend/include/footer');
    } else {


        $config['upload_path'] = './uploads/'; // Specify the path where the image will be uploaded
        $config['allowed_types'] = 'gif|jpg|jpeg|png'; // Allowed image file types
        $config['max_size'] = 2048; // Maximum file size in kilobytes (2MB)
    
        $this->load->library('upload', $config);
    
    if(!$this->upload->do_upload('image')){

        $this->session->set_flashdata('error',$this->upload->display_errors());

           //redirect(base_url('demo'));
     }
     else{

        $this->session->set_flashdata('success','Image successfully uploaded');

        $image_data = $this->upload->data();
       // $image_path = 'uploads/'. $image_data['file_name'];
      $image_path ='./uploads/'. $image_data['file_name'];

      $resident_data = $this->db->get_where('resident', array('resident_id' => $resident_id))->row();

      if (!empty($resident_data->image) && file_exists($resident_data->image)) {
        unlink($resident_data->image);
    }
        // Form validation passed, update the resident's information
        $resident_data =[
            'image' => $image_path,
            'first_name' => $this->input->post('first_name', TRUE),
            'middle_name' => $this->input->post('middle_name', TRUE),
            'last_name' => $this->input->post('last_name', TRUE),
            'name_ex' => $this->input->post('name_ex', TRUE),
            'sex' => $this->input->post('sex', TRUE),
            'birth_date' => $this->input->post('birth_date', TRUE),
            'birth_place' => $this->input->post('birth_place', TRUE),
            'contact' => $this->input->post('contact', TRUE),
            'email' => $this->input->post('email', TRUE),
            'purok' => $this->input->post('purok', TRUE),
            'barangay' => $this->input->post('barangay', TRUE),
            'occupation' => $this->input->post('occupation', TRUE),
            'civil_status' => $this->input->post('civil_status', TRUE),
            'religion' => $this->input->post('religion', TRUE),
            'nationality' => $this->input->post('nationality', TRUE),
            'educational' => $this->input->post('educational', TRUE),
            'monthly_income' => $this->input->post('monthly_income', TRUE),
            'tot_housemember' => $this->input->post('tot_housemember', TRUE),
            'land_own' => $this->input->post('land_own', TRUE),
            'house_own' => $this->input->post('house_own', TRUE),
            'diff_abled' => $this->input->post('diff_abled', TRUE),
          
        ];
    
        $this->db->where('resident_id', $resident_id);
        $update = $this->db->update('resident', $resident_data);

        if ($update) {
            redirect(base_url('index.php/dashboard/view-residents'));
        }
    }

}

    }
    public function view_resident(){

        if(!isset($_SESSION['user'])){
            $this->session->set_flashdata('msg_login','You are not logged in. Please Login First');
            redirect(base_url('index.php/admin'));
        }
        
        $resident_list = $this->db->get('resident')->result();

        $data = [
'resident_list'=>$resident_list
        ];

        $this->load->view('backend/include/header');
        $this->load->view('backend/include/nav');
        $this->load->view('backend/page/view_resident',$data);
        $this->load->view('backend/include/footer'); 

    }

    public function view_blotter(){

        if(!isset($_SESSION['user'])){
            $this->session->set_flashdata('msg_login','You are not logged in. Please Login First');
            redirect(base_url('index.php/admin'));
        }


        $blotter_list = $this->db->get('blotter')->result();

        $data = [
'blotter_list'=>$blotter_list
        ];
        $this->load->view('backend/include/header');
        $this->load->view('backend/include/nav');
        $this->load->view('backend/page/view_blotter',$data);
        $this->load->view('backend/include/footer');
    }
    public function add_blotter()
{
    if (!isset($_SESSION['user'])) {
        $this->session->set_flashdata('msg_login', 'You are not logged in. Please Login First');
        redirect(base_url('index.php/admin'));
    }

    $data['residents'] = $this->Users_model->getResidents(); // Fetch residents from the database
   // Load your view and pass the data


    $this->form_validation->set_rules('complainant', 'Complainant', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('age', 'Age', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('address', 'Address', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('con_complainant', 'Contact # ', 'trim|required');
    $this->form_validation->set_rules('complainee', 'Complainee', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('age_c', 'Age', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('address_c', 'Address', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('con_complainee', 'Contact #', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('complaint', 'Complaint', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('action', 'Action', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('status', 'Status', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('location', 'Location of Incidence', 'trim|required|min_length[2]|max_length[50]');

    $this->form_validation->set_error_delimiters('<p style="color:red;">', '<p>');

    if ($this->form_validation->run() == FALSE) {
        $this->load->view('backend/include/header');
        $this->load->view('backend/include/nav');
        $this->load->view('backend/page/add_blotter', $data);
        $this->load->view('backend/include/footer');
    } else {

        
        $blotter_data = [
            'complainant' => $this->input->post('complainant', TRUE),
            'age' => $this->input->post('age', TRUE),
            'address' => $this->input->post('address', TRUE),
            'con_complainant' => $this->input->post('con_complainant', TRUE),
            'complainee' => $this->input->post('complainee', TRUE),
            'age_c' => $this->input->post('age_c', TRUE),
            'address_c' => $this->input->post('address_c', TRUE),
            'con_complainee' => $this->input->post('con_complainee', TRUE),
            'complaint' => $this->input->post('complaint', TRUE),
            'action' => $this->input->post('action', TRUE),
            'status' => $this->input->post('status', TRUE),
            'location' => $this->input->post('location', TRUE),
        ];

        $insert = $this->db->insert('blotter', $blotter_data);

        if ($insert) {
            $this->session->set_flashdata('success', 'Successfully Added!');
            redirect(base_url('index.php/dashboard/view-blotter'));
        } else {
            $this->session->set_flashdata('error', 'Added Failed!');
            // Handle the case when insertion fails
        }
    }
}

    
    public function edit_blotter($blotter_id) {
        if (!isset($_SESSION['user'])) {
            $this->session->set_flashdata('msg_login', 'You are not logged in. Please Login First');
            redirect(base_url('index.php/admin'));
        }
    // Validate the input if necessary
    
    $this->form_validation->set_rules('complainant','Complainant','trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('age','Age','trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('address','Address','trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('con_complainant','Contact # ','trim|required');
    $this->form_validation->set_rules('complainee','Complainee','trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('age_c','Age','trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('address_c','Address','trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('con_complainee','Contact #','trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('complaint','Complaint','trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('action','Action','trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('status','Status','trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('location','Location of Incidence','trim|required|min_length[2]|max_length[50]');
  
    $this->form_validation->set_error_delimiters('<p style="color:red;">', '<p>');
  
    if ($this->form_validation->run() == FALSE) {
        
        $blotter_data = $this->db->get_where('blotter', array('blotter_id' => $blotter_id))->row();
    
        $data = [
            'blotter_data' => $blotter_data
        ];

        
        $this->load->view('backend/include/header');
        $this->load->view('backend/include/nav');
        $this->load->view('backend/page/edit_blotter',$data);
        $this->load->view('backend/include/footer');
    } else {
        // Form validation passed, update the resident's information
        $blotter_data = [
            'complainant'=>$this->input->post('complainant',TRUE),
            'age'=>$this->input->post('age',TRUE),
            'address'=>$this->input->post('address',TRUE),
            'con_complainant'=>$this->input->post('con_complainant',TRUE),
            'complainee'=>$this->input->post('complainee',TRUE),
            'age_c'=>$this->input->post('age_c',TRUE),
            'address_c'=>$this->input->post('address_c',TRUE),
            'con_complainee'=>$this->input->post('con_complainee',TRUE),
            'complaint'=>$this->input->post('complaint',TRUE),
            'action'=>$this->input->post('action',TRUE),
            'status'=>$this->input->post('status',TRUE),
            'location'=>$this->input->post('location',TRUE),
   
        ];
    
        $this->db->where('blotter_id', $blotter_id);
        $update = $this->db->update('blotter', $blotter_data);

        if ($update) {
            redirect(base_url('index.php/dashboard/view-blotter'));
        }
    }

}

public function ajax_update_blotter_form(){

    $blotter_id = $this->input->post('blotter_id',true);

    $blotter_data  =  $this->db->where('blotter_id',$blotter_id)->get('blotter')->row();
    
    $data = ['blotter_data'=>$blotter_data];

    $this->load->view('backend/page/popup/edit-blotter',$data);
}

    public function delete_blotter($id)
	{
		$this->db->db_debug = TRUE;
		$this->db->where('blotter_id', $id);
		$this->db->delete('blotter');
		redirect('dashboard/view-blotter');
	}

    public function view_brgyofficial(){

        if(!isset($_SESSION['user'])){
            $this->session->set_flashdata('msg_login','You are not logged in. Please Login First');
            redirect(base_url('index.php/admin'));
        }


        $brgyofficial_list = $this->db->get('brgyofficial')->result();

        $data = [
'brgyofficial_list'=>$brgyofficial_list
        ];

        $this->load->view('backend/include/header');
        $this->load->view('backend/include/nav');
        $this->load->view('backend/page/view_brgyofficial',$data);
        $this->load->view('backend/include/footer');
    }
    public function add_brgyofficial()
{
    if (!isset($_SESSION['user'])) {
        $this->session->set_flashdata('msg_login', 'You are not logged in. Please Login First');
        redirect(base_url('index.php/admin'));
    }

    $data['brgyofficial'] = $this->Users_model->getBrgyofficial(); // Fetch residents from the database
   // Load your view and pass the data

    $this->form_validation->set_rules('image','Image','validate_image_upload');
    $this->form_validation->set_rules('position', 'Position', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('middle_name', 'Middle Name', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('contact', 'Contact', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('purok', 'Purok', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('barangay', 'Barangay', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('municipality', 'Municipality', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('province', 'Province', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('start_term', 'Start of Term', 'trim|required');
    $this->form_validation->set_rules('end_term', 'End of Term', 'trim|required');
   

    $this->form_validation->set_error_delimiters('<p style="color:red;">', '<p>');

    if ($this->form_validation->run() == FALSE) {
        $this->load->view('backend/include/header');
        $this->load->view('backend/include/nav');
        $this->load->view('backend/page/add_brgyofficial', $data);
        $this->load->view('backend/include/footer');
    } else {

        $config['upload_path'] = './uploads/'; // Specify the path where the image will be uploaded
        $config['allowed_types'] = 'gif|jpg|jpeg|png'; // Allowed image file types
        $config['max_size'] = 2048; // Maximum file size in kilobytes (2MB)

        $this->load->library('upload', $config);

        
        if($_FILES['image']['name']==''){

            $this->session->set_flashdata('error','Please select an image');

            //  redirect(base_url('demo'));	
        }

        if(!$this->upload->do_upload('image')){
    
            $this->session->set_flashdata('error',$this->upload->display_errors());
 
               //redirect(base_url('demo'));
         }
         else{

            $this->session->set_flashdata('success','Image successfully uploaded');

            $image_data = $this->upload->data();
           // $image_path = 'uploads/'. $image_data['file_name'];
          $image_path ='./uploads/'. $image_data['file_name'];

        $brgyofficial_data = [
            'image' => $image_path,
            'position' => $this->input->post('position', TRUE),
            'first_name' => $this->input->post('first_name', TRUE),
            'middle_name' => $this->input->post('middle_name', TRUE),
            'last_name' => $this->input->post('last_name', TRUE),
            'contact' => $this->input->post('contact', TRUE),
            'purok' => $this->input->post('purok', TRUE),
            'barangay' => $this->input->post('barangay', TRUE),
            'municipality' => $this->input->post('municipality', TRUE),
            'province' => $this->input->post('province', TRUE),
            'start_term' => $this->input->post('start_term', TRUE),
            'end_term' => $this->input->post('end_term', TRUE),
          
        ];
// Upload the image file if it exists
        if (isset($_FILES['image_file']) && $_FILES['image_file']['name'] !== '') {
            $config['upload_path'] = './uploads/'; // Set the upload path for the images
            $config['allowed_types'] = 'gif|jpg|jpeg|png'; // Allowed image types
            $config['max_size'] = 2048; // Maximum file size in kilobytes (2MB)

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('image_file')) {
                $upload_error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $upload_error);
                redirect(base_url('index.php/dashboard/add-brgyofficial'));
            } else {
                $upload_data = $this->upload->data();
                $resident_data['image'] = $upload_data['file_name']; // Set the 'image' field with the uploaded image file name
            }
        }

        $insert = $this->db->insert('brgyofficial', $brgyofficial_data);

        if ($insert) {
            $this->session->set_flashdata('success', 'Successfully Added!');
            redirect(base_url('index.php/dashboard/view-brgyofficial'));
        } else {
            $this->session->set_flashdata('error', 'Added Failed!');
            // Handle the case when insertion fails
        }
    }
}
}
    
    public function edit_brgyofficial($brgyofficial_id) {
        if (!isset($_SESSION['user'])) {
            $this->session->set_flashdata('msg_login', 'You are not logged in. Please Login First');
            redirect(base_url('index.php/admin'));
        }
    // Validate the input if necessary
    
    $this->form_validation->set_rules('image','Image','validate_image_upload');
    $this->form_validation->set_rules('position', 'Position', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('middle_name', 'Middle Name', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('contact', 'Contact', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('purok', 'Purok', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('barangay', 'Barangay', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('municipality', 'Municipality', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('province', 'Province', 'trim|required|min_length[2]|max_length[50]');
    $this->form_validation->set_rules('start_term', 'Start of Term', 'trim|required');
    $this->form_validation->set_rules('end_term', 'End of Term', 'trim|required');
    $this->form_validation->set_error_delimiters('<p style="color:red;">', '<p>');
  
    if ($this->form_validation->run() == FALSE) {
        
        $brgyofficial_data = $this->db->get_where('brgyofficial', array('brgyofficial_id' => $brgyofficial_id))->row();
    
        $data = [
            'brgyofficial_data' => $brgyofficial_data
        ];

        
        $this->load->view('backend/include/header');
        $this->load->view('backend/include/nav');
        $this->load->view('backend/page/edit_brgyofficial',$data);
        $this->load->view('backend/include/footer');
    } else {

        $config['upload_path'] = './uploads/'; // Specify the path where the image will be uploaded
        $config['allowed_types'] = 'gif|jpg|jpeg|png'; // Allowed image file types
        $config['max_size'] = 2048; // Maximum file size in kilobytes (2MB)
    
        $this->load->library('upload', $config);
    
    if(!$this->upload->do_upload('image')){

        $this->session->set_flashdata('error',$this->upload->display_errors());

           //redirect(base_url('demo'));
     }
     else{

        $this->session->set_flashdata('success','Image successfully uploaded');

        $image_data = $this->upload->data();
       // $image_path = 'uploads/'. $image_data['file_name'];
      $image_path ='./uploads/'. $image_data['file_name'];

      $brgyofficial_data = $this->db->get_where('brgyofficial', array('brgyofficial_id' => $brgyofficial_id))->row();

      if (!empty($brgyofficial_data->image) && file_exists($brgyofficial_data->image)) {
        unlink($brgyofficial_data->image);
    }
}
        // Form validation passed, update the resident's information
        $brgyofficial_data = [

            'image' => $image_path,
            'position' => $this->input->post('position', TRUE),
            'first_name' => $this->input->post('first_name', TRUE),
            'middle_name' => $this->input->post('middle_name', TRUE),
            'last_name' => $this->input->post('last_name', TRUE),
            'contact' => $this->input->post('contact', TRUE),
            'purok' => $this->input->post('purok', TRUE),
            'barangay' => $this->input->post('barangay', TRUE),
            'municipality' => $this->input->post('municipality', TRUE),
            'province' => $this->input->post('province', TRUE),
            'start_term' => $this->input->post('start_term', TRUE),
            'end_term' => $this->input->post('end_term', TRUE),
          
        ];
        if (isset($_FILES['image_file']) && $_FILES['image_file']['name'] !== '') {
            $config['upload_path'] = './uploads/'; // Set the upload path for the images
            $config['allowed_types'] = 'gif|jpg|jpeg|png'; // Allowed image types
            $config['max_size'] = 2048; // Maximum file size in kilobytes (2MB)

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('image_file')) {
                $upload_error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $upload_error);
                redirect(base_url('index.php/dashboard/add-brgyofficial'));
            } else {
                $upload_data = $this->upload->data();
                $resident_data['image'] = $upload_data['file_name']; // Set the 'image' field with the uploaded image file name
            }
        $this->db->where('brgyofficial_id', $brgyofficial_id);
        $update = $this->db->update('brgyofficial', $brgyofficial_data);

        if ($update) {
            redirect(base_url('index.php/dashboard/view-brgyofficial'));
        }
    }

}
    }
public function ajax_update_brgyofficial_form(){

    $brgyofficial_id = $this->input->post('brgyofficial_id',true);

    $brgyofficial_data  =  $this->db->where('brgyofficial_id',$brgyofficial_id)->get('brgyofficial')->row();
    
    $data = ['brgyofficial_data'=>$brgyofficial_data];

    $this->load->view('backend/page/popup/edit_brgyofficial',$data);
}

    public function delete_brgyofficial($id)
	{
		$this->db->db_debug = TRUE;
		$this->db->where('brgyofficial_id', $id);
		$this->db->delete('brgyofficial');
		redirect('dashboard/view-brgyofficial');
	}

    public function ajax_update_resident_form(){

        $resident_id = $this->input->post('resident_id',true);
    
        $resident_data  =  $this->db->where('resident_id',$resident_id)->get('resident')->row();
        
        $data = ['resident_data'=>$resident_data];
    
        $this->load->view('backend/page/popup/edit-resident',$data);
    }
    
        public function delete_resident($id)
        {
            $this->db->db_debug = TRUE;
            $this->db->where('resident_id', $id);
            $this->db->delete('resident');
            redirect('dashboard/view-residents');
        }
    
     public function fetch_resident($resident_id)
    {
        // Fetch resident information based on the resident ID
        $resident = $this->db->get_where('resident', array('resident_id' => $resident_id))->row();

        if ($resident) {
            // Return the resident information as JSON response
            $response = [
                'resident_id' => $resident->resident_id,
                'first_name' => $resident->first_name,
                'last_name' => $resident->last_name,
                'birth_date' => $resident->birth_date,
                'sex' => $resident->sex,
                'street' => $resident->street,
                'purok' => $resident->purok,
                'barangay' => $resident->barangay,
                'contact' => $resident->contact,
                'religion' => $resident->religion,
                'civil_status' => $resident->civil_status,
                'nationality' => $resident->nationality
                // Add more properties as needed
            ];

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        } else {
            // Return an error response if resident not found
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Resident not found']));
        }
    }
    public function ajax_view_resident_form(){

        $resident_id = $this->input->post('resident_id',true);
    
        $resident_data  =  $this->db->where('resident_id',$resident_id)->get('resident')->row();
        
        $data = ['resident_data'=>$resident_data];
    
        $this->load->view('backend/page/popup/view_residentdetails',$data);
    }
    public function view_residentdetails($resident_id) {
        if (!isset($_SESSION['user'])) {
            $this->session->set_flashdata('msg_login', 'You are not logged in. Please Login First');
            redirect(base_url('index.php/admin'));
        }
    
        // Load the resident data from the database using the model
        $resident_data = $this->Users_model->get_resident_data_by_id($resident_id);
    
        // Debug: Check if the data is fetched successfully
        var_dump($resident_id);
        var_dump($resident_data);
    
        if ($resident_data === null) {
            // Handle the case where the resident data is not available or not found
            $data['error_message'] = 'Resident data not available';
        } else {
            // Pass the data to the view
            $data['resident_data'] = $resident_data;
        }
    
        // Load the view with the data
        $this->load->view('backend/page/popup/view_residentdetails', $data);
    }
    public function viewall_brgyofficial()
{
    if (!isset($_SESSION['user'])) {
        $this->session->set_flashdata('msg_login', 'You are not logged in. Please Login First');
        redirect(base_url('index.php/admin'));
    }

    // Load the model for officials
    $this->load->model('Users_model');

    // Fetch officials' data from the database
    $data['brgyofficial'] = $this->Users_model->get_all_officials();
    $data['brgyofficial_list'] = $this->db->get('brgyofficial')->result();
    // Load the view to display officials
    $this->load->view('backend/include/header');
    $this->load->view('backend/include/nav');
    $this->load->view('backend/page/viewall_brgyofficial', $data);
    $this->load->view('backend/include/footer');
}
    
public function test(){
    
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML('<html>
<head><!DOCTYPE html>
<html>
<head>
<title>Barangay Clearance</title>
<style>
/* Your CSS styles here */
</style>
</head>
<body>
<!-- Your HTML content here using $resident_data to populate the details -->
<div class="header">
    Republic of the Philippines<br>
    Province/City/Municipality of XXX<br>
    Barangay XXX<br>
    Barangay Clearance
</div>
<div class="content">
    <p>To whom it may concern:</p>
    <p>This is to certify that <strong>Catherine </strong> is a resident of Barangay XXX and is of good moral character.</p>
    <p>This clearance is issued upon the request of the above-named person for whatever legal purpose it may serve him/her best.</p>
    <p>Issued this <strong>'.date('dS F, Y').'</strong> day at Barangay XXX, Province/City/Municipality of XXX.</p>
</div>
<div class="footer">
    <p>Barangay Captain</p>
    <p>Barangay XXX</p>
</div>
</body>
</html>');
$mpdf->Output();

}


    
}
   ?>