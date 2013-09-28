<?php
namespace wsm\samodel\apiBaseCamp;

class Basecamp {
  
  
  protected $request;
  protected $baseurl;
  protected $format;
  protected $username;
  protected $password;
  protected $request_body;
  
  public function __construct ($baseurl,$username=null,$password=null,$format='xml') {
    $this->setBaseurl($baseurl);
    $this->setFormat($format);
    $this->setUsername($username);
    $this->setPassword($password);
    $this->setFormat($format);
  }
  
  public function createTodoListForProject(
        $project_id,
        $name,
        $description=null,
        $milestone_id=null,
        $private=null,
        $tracked=null
    ) {
    if(!preg_match('!^\d+$!',$project_id))
      throw new \InvalidArgumentException("project id must be a number.");
    if(empty($name))
      throw new \InvalidArgumentException("todo list name cannot be empty.");
    
    $content = array(
              'todo-list'=>array(
                'name'=>$name,
                'description'=>$description,
                'milestone-id'=>$milestone_id,
                'private'=>$private,
                'tracked'=>$tracked
                )
            );
    
    $this->setupRequestBody($content);
    
    $response = $this->processRequest("{$this->baseurl}projects/{$project_id}/todo_lists.xml","POST");
    
    if(preg_match('!(\d+)$!',$response['location'],$match))
      $response['id'] = $match[1];
    else
      $response['id'] = null;
    return $response;    
  }
  
  public function createCommentForItem(
    $resource_type,
    $resource_id,
    $body
    ) {
    if(empty($body))
      throw new InvalidArgumentException("comment body cannot be empty.");
    
    $body = array(
              'comment'=>array(
                'body'=>$body
                )
            );
    
    $this->setupRequestBody($body);
    
    $response = $this->processRequest("{$this->baseurl}{$resource_type}/{$resource_id}/comments.xml","POST");
    
    if(preg_match('!(\d+)\.xml!',$response['location'],$match))
      $response['id'] = $match[1];
    else
      $response['id'] = null;
    return $response;    
  }
  
  
  public function createCommentForMessage(
    $resource_type,
    $resource_id,
    $body
    ) {
    if(empty($body))
      throw new InvalidArgumentException("comment body cannot be empty.");
    
    $body = array(
              'comment'=>array(
                'body'=>$body
                )
            );
    
    $this->setupRequestBody($body);
    
    $response = $this->processRequest("{$this->baseurl}{$resource_type}/{$resource_id}/comments.xml","POST");
    
    if(preg_match('!(\d+)\.xml!',$response['location'],$match))
      $response['id'] = $match[1];
    else
      $response['id'] = null;
    return $response;    
  }
  
  public function createMessageForProject(
        $project_id,
        $title,
        $body=null,
        $category_id=null,
        $extended_body=null,
        $milestone_id=0,
        $private=false,
        $notify_people=array(),
        $attachments=array()
    ) {
    if(!preg_match('!^\d+$!',$project_id))
      throw new \InvalidArgumentException("project id must be a number.");
    if(empty($title))
      throw new \InvalidArgumentException("title cannot be empty.");
    if(!is_array($notify_people))
      throw new \InvalidArgumentException("notify people must be an array.");
    if(!is_array($attachments))
      throw new \InvalidArgumentException("attachments must be an array.");
    
    $body = array(
              'post'=>array(
                'category-id'=>$category_id,
                'title'=>$title,
                'body'=>$body,
                'extended-body'=>$extended_body,
                'private'=>$private
                )
            );
 
    if(!empty($notify_people)) {
      foreach($notify_people as $key=>$val)
        $body['notify:'.$key] = $val;
    }
    
    if(!empty($attachments)) {
      foreach($attachments as $key=>$attachment)
        $attach_info = array(
          'name' => $attachment[0],
          'file' => array(
            'file' => $attachment[1],
            'content-type' => $attachment[2],
            'original-filename' => $attachment[3]
            )
        );
        $body['attachments:'.$key] = $attach_info;
    }
    
    $this->setupRequestBody($body);    
    $response = $this->processRequest("{$this->baseurl}projects/{$project_id}/posts.xml","POST");
   
    if(preg_match('!(\d+)\.xml!',$response['location'],$match))
      $response['id'] = $match[1];
    else
      $response['id'] = null;
    return $response;    
  }
  
 public function deleteMessage($message_id) {
    if(!preg_match('!^\d+$!',$message_id))
      throw new InvalidArgumentException("message id must be a number.");
    return $this->processRequest("{$this->baseurl}posts/{$message_id}.xml","DELETE");
  } 
  
  public function deleteComment($comment_id) {
    if(!preg_match('!^\d+$!',$comment_id))
      throw new InvalidArgumentException("comment id must be a number.");
    return $this->processRequest("{$this->baseurl}comments/{$comment_id}.xml","DELETE");
  }
  
  public function deleteTodoList($todo_list_id) {
    if(!preg_match('!^\d+$!',$todo_list_id))
      throw new InvalidArgumentException("todo list id must be a number.");
    return $this->processRequest("{$this->baseurl}todo_lists/{$todo_list_id}.xml","DELETE");
  }
  
  public function deleteTodoItem($todo_id) {
    if(!preg_match('!^\d+$!',$todo_id))
      throw new InvalidArgumentException("todo id must be a number.");
    return $this->processRequest("{$this->baseurl}todo_items/{$todo_id}.xml","DELETE");
  }
  
  public function createTodoItemForList(
    $todo_list_id,
    $content,
    $responsible_party_type=null,
    $responsible_party_id=null,
    $notify=null
    ) {
    if(!preg_match('!^\d+$!',$todo_list_id))
      throw new InvalidArgumentException("todo list id must be a number.");
    if(empty($content))
      throw new InvalidArgumentException("todo item content cannot be empty.");
  	$responsible_party_type = strtolower($responsible_party_type);
    if(isset($responsible_party_type) && !in_array($responsible_party_type,array('person','company')))
      throw new InvalidArgumentException("'{$responsible_party_type}' is not a valid responsible party type.");
    if(!empty($responsible_party_type) && empty($responsible_party_id))
      throw new InvalidArgumentException("responsible party id cannot be empty.");
    
    if($responsible_party_type == 'person')
      $resp_party = $responsible_party_id;
    elseif($responsible_party_type == 'company')
      $resp_party = "c{$responsible_party_id}";
    else
      $resp_party = '';      
      
    $data = array(
              'todo-item'=>array(
                'content'=>$content,
                'responsible-party'=>$resp_party,
                'notify type="boolean"'=>$notify
                )
            );
    
    $this->setupRequestBody($data);
    
    $response = $this->processRequest("{$this->baseurl}todo_lists/{$todo_list_id}/todo_items.xml","POST");
    // set new list id
    if(preg_match('!(\d+)$!',$response['location'],$match))
      $response['id'] = $match[1];
    else
      $response['id'] = null;
    return $response;    
  }
  

  public function getUsername()
  {
    return $this->username;
  }

  public function setUsername($username)
  {
    if(empty($username))
      throw new InvalidArgumentException("username cannot be empty.");
    $this->username = $username;
  }  

  public function getFormat()
  {
    return $this->format;
  }

  public function setFormat($format)
  {
    if(empty($format))
      throw new InvalidArgumentException("format cannot be empty.");
  	$format = strtolower($format);
    if(!in_array($format,array('xml','simplexml')))
      throw new InvalidArgumentException("'{$format}' is not a valid format.");
    $this->format = $format;
  }  

  public function getPassword()
  {
    return $this->password;
  }

  public function setPassword($password)
  {
    if(empty($password))
      throw new InvalidArgumentException("password cannot be empty.");
    $this->password = $password;
  }  

  public function getRequestBody()
  {
    return $this->request_body;
  }

  public function setRequestBody($body)
  {
    $this->request_body = $body;
  }  

  public function getBaseurl()
  {
    return $this->baseurl;
  }

  public function setBaseurl($url)
  {
    if(empty($url))
      throw new InvalidArgumentException("Base URL cannot be empty.");
    if(!preg_match('!^https?://!i',$url))
      $url = 'http://' . $url;
    if(substr($url,-1) !== '/')
      $url .= '/';
    $this->baseurl = $url;
  }
  
  private function setupRequestBody($body) {
    $request_body = array('request'=>$body);
    $this->setRequestBody($this->createXMLFromArray($request_body));
    
  }  
  
  private function processRequest($url,$type,$format=null) {
    
    $this->request = new RestRequest($url,$type);
    $this->request->setUsername($this->username);
    $this->request->setPassword($this->password);

    $this->request->setRequestBody($this->request_body);
    //print_r($this->request->setRequestBody($this->request_body));
    $this->request->execute();
    
    $response_info = $this->request->getResponseInfo();
    $response_content = $this->request->getResponseBody();

    $return['headers'] =   substr($response_content,0,$response_info['header_size']);
    $return['body'] = substr($response_content,$response_info['header_size']);
    
    if(preg_match('!^Status: (.*)$!m',$return['headers'],$match))
      $return['status'] = trim($match[1]);
    else
      $return['status'] = null;

    if(preg_match('!^Location: (.*)$!m',$return['headers'],$match))
      $return['location'] = trim($match[1]);
    else
      $return['location'] = null;
      
    if(!isset($format))
      $format = $this->format;
      
    $return['body'] = trim($return['body']);
    if(!empty($return['body']) && $format == 'simplexml') {
      $return['body'] = new SimpleXMLElement($return['body']);
    }
    
    unset($this->request);
    $this->request_body = null;
    
    return $return;
  }
  
  private function createXMLFromArray($array,$level=0) {
    $xml = '';
    foreach($array as $key=>$val) {
        $attrs = '';
  
        if(($spos = strpos($key,' '))!==false) {
          $attrs = substr($key,$spos);
          $key = substr($key,0,$spos);
        }
  
        if(($colpos = strpos($key,':'))!==false)
          $key = substr($key,0,$colpos);
  
        $xml .= sprintf("%s<%s>%s</%s>\n",
          str_repeat('  ',$level),
          htmlspecialchars($key).$attrs,
          is_array($val) ? "\n".$this->createXMLFromArray($val,$level+1).str_repeat('  ',$level) : htmlspecialchars($val),
          htmlspecialchars($key)
        );
    }
    return $xml;
  }
}




class RestRequest
{
  protected $url;
  protected $verb;
  protected $requestBody;
  protected $requestLength;
  protected $username;
  protected $password;
  protected $contentType;
  protected $acceptType;
  protected $responseBody;
  protected $responseInfo;
  
  public function __construct ($url = null, $verb = 'GET', $requestBody = null)
  {
    $this->url            = $url;
    $this->verb           = $verb;
    $this->requestBody    = $requestBody;
    $this->requestLength  = 0;
    $this->username       = null;
    $this->password       = null;
    $this->contentType    = 'application/xml';
    $this->acceptType     = 'application/xml';
    $this->responseBody   = null;
    $this->responseInfo   = null;    
  }
  
  public function flush ()
  {
    $this->requestBody    = null;
    $this->requestLength  = 0;
    $this->verb        = 'GET';
    $this->responseBody    = null;
    $this->responseInfo    = null;
  }
  
  public function execute ()
  {
    $ch = curl_init();
    $this->setAuth($ch);
    
    try
    {
      switch (strtoupper($this->verb))
      {
        case 'GET':
          $this->executeGet($ch);
          break;
        case 'POST':
          $this->executePost($ch);
          break;
        case 'POSTFILE':
          $this->executePostFile($ch);
          break;
        case 'PUT':
          $this->executePut($ch);
          break;
        case 'DELETE':
          $this->executeDelete($ch);
          break;
        default:
          throw new InvalidArgumentException('Current verb (' . $this->verb . ') is an invalid REST verb.');
      }
    }
    catch (InvalidArgumentException $e)
    {
      curl_close($ch);
      throw $e;
    }
    catch (Exception $e)
    {
      curl_close($ch);
      throw $e;
    }
    
  }
    
  protected function executeGet ($ch)
  {    
    $this->doExecute($ch);  
  }
  
  protected function executePost ($ch)
  {    
    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->requestBody);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/xml', 'Content-Type: application/xml'));
    
    $this->doExecute($ch);  
  }

  protected function executePostFile ($ch)
  {    
    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->requestBody);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/xml', 'Content-Type: application/octet-stream'));
    curl_setopt($ch, CURLOPT_POST, 1);
    
    $this->doExecute($ch);      
  }
  
  protected function executePut ($ch)
  {
    $this->requestLength = strlen($this->requestBody);
    
    $fh = fopen('php://memory', 'rw');
    fwrite($fh, $this->requestBody);
    rewind($fh);
    
    curl_setopt($ch, CURLOPT_INFILE, $fh);
    curl_setopt($ch, CURLOPT_INFILESIZE, $this->requestLength);
    curl_setopt($ch, CURLOPT_PUT, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/xml', 'Content-Type: application/xml'));
    
    $this->doExecute($ch);
    
    fclose($fh);
  }
  
  protected function executeDelete ($ch)
  {
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/xml', 'Content-Type: application/xml'));
    
    $this->doExecute($ch);
  }
  
  protected function doExecute (&$curlHandle)
  {
    $this->setCurlOpts($curlHandle);
    $this->responseBody = curl_exec($curlHandle);
    $this->responseInfo = curl_getinfo($curlHandle);
    curl_close($curlHandle);
  }
  
  protected function setCurlOpts (&$curlHandle)
  {
    curl_setopt($curlHandle, CURLOPT_TIMEOUT, 10);
    curl_setopt($curlHandle, CURLOPT_URL, $this->url);
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlHandle, CURLOPT_HEADER, true);
    curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, !preg_match("!^https!i",$this->url));
  }
  
  protected function setAuth (&$curlHandle)
  {
    if ($this->username !== null && $this->password !== null)
    {
      curl_setopt($curlHandle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($curlHandle, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    }
  }
  
  public function getAcceptType ()
  {
    return $this->acceptType;
  } 
  
  public function setAcceptType ($acceptType)
  {
    $this->acceptType = $acceptType;
  } 
  
  public function getPassword ()
  {
    return $this->password;
  } 
  
  public function setPassword ($password)
  {
    $this->password = $password;
  } 
  
  public function getResponseBody ()
  {
    return $this->responseBody;
  } 
  
  public function getResponseInfo ()
  {
    return $this->responseInfo;
  } 
  
  public function getUrl ()
  {
    return $this->url;
  } 
  
  public function setUrl ($url)
  {
    $this->url = $url;
  } 
  
  public function getUsername ()
  {
    return $this->username;
  } 
  
  public function setUsername ($username)
  {
    $this->username = $username;
  } 
  
  public function getVerb ()
  {
    return $this->verb;
  } 
  
  public function setVerb ($verb)
  {
    $this->verb = $verb;
  } 

  public function getRequestBody ()
  {
    return $this->requestBody;
  } 
  
  public function setRequestBody ($body)
  {
    $this->requestBody = $body;
  } 

}
?>
