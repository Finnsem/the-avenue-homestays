<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});
//end
// Routes

$app->get('/homestay', function ($request, $response, $args) {
    $sql = $this->db->prepare("SELECT * FROM homestays"); 
    $sql->execute();
    $data = $sql->fetchAll(PDO::FETCH_ASSOC); // Fetch data as associative array
    return $response->withJson($data, 200); // Return data directly
});


        $app->get('/homestay/{id}', function ($request, $response, $args) {
            $homestayId = $args['id'];
            // Prepare SQL query with a parameter for the homestay ID
            $sql = $this->db->prepare("SELECT * FROM homestays WHERE id = :id");
            // Bind the homestay ID parameter
            $sql->bindParam(':id', $homestayId);
            // Execute the query
            $sql->execute();
            // Fetch the homestay data
            $homestay = $sql->fetch();
            // Check if homestay exists
            if (!$homestay) {
                // Return a 404 Not Found response if homestay does not exist
                return $response->withStatus(404)->withJson(['error' => 'Homestay not found']);
            }        
            // Return the homestay data as JSON
            return $response->withHeader('Content-Type', 'application/json')
                            ->withHeader('Access-Control-Allow-Origin', '*')
                            ->withJson($homestay);
        });


                $app->get('/owner/{id}', function ($request, $response, $args) {
                    $ownerId = $args['id'];
                    // Prepare SQL query with a parameter for the homestay ID
                    $sql = $this->db->prepare("SELECT * FROM owners WHERE id = :id");
                    // Bind the homestay ID parameter
                    $sql->bindParam(':id', $ownerId);
                    // Execute the query
                    $sql->execute();
                    // Fetch the homestay data
                    $owner = $sql->fetch();
                    // Check if homestay exists
                    if (!$owner) {
                        // Return a 404 Not Found response if homestay does not exist
                        return $response->withStatus(404)->withJson(['error' => 'Homestay not found']);
                    }        
                    // Return the homestay data as JSON
                    return $response->withHeader('Content-Type', 'application/json')
                                    ->withHeader('Access-Control-Allow-Origin', '*')
                                    ->withJson($owner);
                });
                    

  

        $app->get('/booking', function ($request, $response, $args) {
            $sql = $this->db->prepare("SELECT * FROM bookings"); 
            $sql->execute();
            $data = $sql->fetchAll(PDO::FETCH_ASSOC); // Fetch data as associative array
            return $response->withJson($data, 200); // Return data directly
        });

        $app->get('/booking/{id}', function ($request, $response, $args) {
            $bookingId = $args['id'];
            // Prepare SQL query with a parameter for the homestay ID
            $sql = $this->db->prepare("SELECT * FROM bookings WHERE id = :id");
            // Bind the homestay ID parameter
            $sql->bindParam(':id', $bookingId);
            // Execute the query
            $sql->execute();
            // Fetch the homestay data
            $booking = $sql->fetch();
            // Check if homestay exists
            if (!$booking) {
                // Return a 404 Not Found response if homestay does not exist
                return $response->withStatus(404)->withJson(['error' => 'booking not found']);
            }        
            // Return the homestay data as JSON
            return $response->withHeader('Content-Type', 'application/json')
                            ->withHeader('Access-Control-Allow-Origin', '*')
                            ->withJson($booking);
        });
    
            $app->get('/owner', function ($request, $response, $args) {
                $sql = $this->db->prepare("SELECT * FROM owners"); 
                $sql->execute();
                $data = $sql->fetchAll(PDO::FETCH_ASSOC); // Fetch data as associative array
                return $response->withJson($data, 200); // Return data directly
            });

            

            $app->get('/guest', function ($request, $response, $args) {
                $sql = $this->db->prepare("SELECT * FROM guests"); 
                $sql->execute();
                $data = $sql->fetchAll();
                return $this->response->withJson($data);
                });
    

                $app->post('/homestay/add', function ($request, $response, $args) {
                    // Get the request body data
                    $input = $request->getParsedBody();
                
                    // Retrieve the database connection from the container
                    $db = $this->get('db');
                    
                    // Prepare the SQL query
                    $sql = 'INSERT INTO homestays (owner_id, address, price, rooms_available) VALUES (:owner_id, :address, :price, :rooms_available)';
                    $stmt = $db->prepare($sql);
                    
                    // Bind parameters
                    $stmt->bindParam(':owner_id', $input['owner_id']);
                    $stmt->bindParam(':address', $input['address']);
                    $stmt->bindParam(':price', $input['price']);
                    $stmt->bindParam(':rooms_available', $input['rooms_available']);
                    
                    // Execute the query
                    $stmt->execute();
                    
                    // Return success message
                    return $response->withJson(['message' => 'Homestay added successfully'], 200);
                });
                

    $app->post('/owner/add', function (Request $request, Response $response, array $args) {
        $input = $request->getParsedBody();
        $db = $this->get('db'); // Retrieve the database connection from the container
        
        // Prepare the SQL query
        $sql = 'INSERT INTO owners (name, email, password) VALUES (:name, :email, :password)';
        $stmt = $db->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':name', $input['name']); // Corrected binding for ':name'
        $stmt->bindParam(':email', $input['email']); // Corrected binding for ':email'
        $stmt->bindParam(':password', $input['password']); // Corrected binding for ':password'
        
        // Execute the query
        $stmt->execute();
        
        // Return the last inserted ID
        return $response->withJson(['message' => 'owner added successfully'], 200);
    });

        $app->post('/guest/add', function (Request $request, Response $response, array $args) {
            $input = $request->getParsedBody();
            $db = $this->get('db'); // Retrieve the database connection from the container
            
            // Prepare the SQL query
            $sql = 'INSERT INTO guests (name, email, password) VALUES (:name, :email, :password)';
            $stmt = $db->prepare($sql);
            
            // Bind parameters
            $stmt->bindParam(':name', $input['name']); // Corrected binding for ':name'
            $stmt->bindParam(':email', $input['email']); // Corrected binding for ':email'
            $stmt->bindParam(':password', $input['password']); // Corrected binding for ':password'
            
            // Execute the query
            $stmt->execute();
            
            // Return the last inserted ID
            return $response->withJson(['id' => $db->lastInsertId()]);
        });
        

            $app->post('/booking/add', function (Request $request, Response $response, array $args) {
                $input = $request->getParsedBody();
                $db = $this->get('db'); // Retrieve the database connection from the container
                
                // Prepare the SQL query
                $sql = 'INSERT INTO bookings (homestay_id, guest_id, num_guests, check_in_date, check_out_date ) VALUES (:homestay_id, :guest_id, :num_guests, :check_in_date, :check_out_date)';
                $stmt = $db->prepare($sql);
                
                // Bind parameters
                $stmt->bindParam(':homestay_id', $input['homestay_id']); // Corrected binding for ':name'
                $stmt->bindParam(':guest_id', $input['guest_id']); // Corrected binding for ':email'
                $stmt->bindParam(':num_guests', $input['num_guests']); // Corrected binding for ':password'
                $stmt->bindParam(':check_in_date', $input['check_in_date']); // Corrected binding for ':email'
                $stmt->bindParam(':check_out_date', $input['check_out_date']); // Corrected binding for ':password'
                // Execute the query
                $stmt->execute();
                
                // Return the last inserted ID
                return $response->withJson(['message' => 'owner added successfully'], 200);
            });
            
            $app->put('/homestay/update/{id}', function ($request, $response, $args) {
                // Get the request body data
                $input = $request->getParsedBody();
                
                // Retrieve the database connection from the container
                $db = $this->get('db');
                
                // Check if owner_id exists in the owners table
                $ownerIdExists = $db->query("SELECT COUNT(*) FROM owners WHERE id = {$input['owner_id']}")->fetchColumn();
                if (!$ownerIdExists) {
                    return $response->withStatus(404)->withJson(['error' => 'Owner not found']);
                }
                
                // Prepare the SQL query for updating homestay
                $sql = 'UPDATE homestays SET owner_id = :owner_id, address = :address, price = :price, rooms_available = :rooms_available WHERE id = :id';
                $stmt = $db->prepare($sql);
                
                // Bind parameters
                $stmt->bindParam(':owner_id', $input['owner_id']);
                $stmt->bindParam(':address', $input['address']);
                $stmt->bindParam(':price', $input['price']);
                $stmt->bindParam(':rooms_available', $input['rooms_available']);
                $stmt->bindParam(':id', $args['id']);
                
                // Execute the query
                $stmt->execute();
                
                // Return success message
                return $response->withJson(['message' => 'Homestay updated successfully']);
            });
            
            

            $app->put('/owner/update/{id}', function (Request $request, Response $response, array $args) {
                $input = $request->getParsedBody();
                $db = $this->get('db'); // Retrieve the database connection from the container
                
                
                // Prepare the SQL query
                $sql = 'UPDATE owners SET name = :name, email = :email, password = :password WHERE id = :id';
                $stmt = $db->prepare($sql);
                
                // Bind parameters
                $stmt->bindParam(':name', $input['name']);
                $stmt->bindParam(':email', $input['email']);
                $stmt->bindParam(':password', $input['password']);
                $stmt->bindParam(':id', $args['id']);
                
                // Execute the query
                $stmt->execute();
                
                // Return success message
                return $response->withJson(['message' => 'owners updated successfully']);
            });

            $app->put('/guest/update/{id}', function (Request $request, Response $response, array $args) {
                $input = $request->getParsedBody();
                $db = $this->get('db'); // Retrieve the database connection from the container
                
                
                // Prepare the SQL query
                $sql = 'UPDATE guests SET name = :name, email = :email, password = :password WHERE id = :id';
                $stmt = $db->prepare($sql);
                
                // Bind parameters
                $stmt->bindParam(':name', $input['name']);
                $stmt->bindParam(':email', $input['email']);
                $stmt->bindParam(':password', $input['password']);
                $stmt->bindParam(':id', $args['id']);
                
                // Execute the query
                $stmt->execute();
                
                // Return success message
                return $response->withJson(['message' => 'owners updated successfully']);
            });
            

            $app->put('/booking/update/{id}', function (Request $request, Response $response, array $args) {
                $input = $request->getParsedBody();
                $db = $this->get('db'); // Retrieve the database connection from the container
                
                
                // Prepare the SQL query
                $sql = 'UPDATE bookings SET homestay_id = :homestay_id, guest_id = :guest_id, num_guests = :num_guests , check_in_date = :check_in_date, check_out_date = :check_out_date WHERE id = :id';
                $stmt = $db->prepare($sql);
                
                // Bind parameters
                $stmt->bindParam(':homestay_id', $input['homestay_id']);
                $stmt->bindParam(':guest_id', $input['guest_id']);
                $stmt->bindParam(':num_guests', $input['num_guests']);
                $stmt->bindParam(':check_in_date', $input['check_in_date']);
                $stmt->bindParam(':check_out_date', $input['check_out_date']);
                $stmt->bindParam(':id', $args['id']);
                
                // Execute the query
                $stmt->execute();
                
                // Return success message
                return $response->withJson(['message' => 'owners updated successfully']);
            });


            // Define the route for deleting a homestay by ID
$app->delete('/homestays/del/{id}', function ($request, $response, $args) {
    // Get the ID from the route parameters
    $homestayId = $args['id'];
    
    // Prepare the DELETE query
    $sql = $this->db->prepare("DELETE FROM homestays WHERE id = :id");
    // Bind the parameter
    $sql->bindParam(':id', $homestayId);
    
    // Execute the query
    $sql->execute();
    
    // Check if any rows were affected (if the homestay with the given ID existed)
    if ($sql->rowCount() > 0) {
        // Return success message if the homestay was deleted
        return $response->withJson(['message' => 'Homestay deleted successfully'], 200);
    } else {
        // Return error message if the homestay with the given ID was not found
        return $response->withJson(['message' => 'Homestay not found'], 404);
    }
});

            
            
            $app->delete('/guest/del/{id}', function (Request $request, Response $response, array $args) {
                $db = $this->get('db'); // Retrieve the database connection from the container
                
                // Prepare the SQL query
                $sql = 'DELETE FROM guests WHERE id = :id';
                $stmt = $db->prepare($sql);
                
                // Bind parameter
                $stmt->bindParam(':id', $args['id']);
                
                // Execute the query
                $stmt->execute();
                
                // Return success message
                return $response->withJson(['message' => 'guest deleted successfully']);
            });

            $app->delete('/booking/del/{id}', function (Request $request, Response $response, array $args) {
                $db = $this->get('db'); // Retrieve the database connection from the container
                
                // Prepare the SQL query
                $sql = 'DELETE FROM bookings WHERE id = :id';
                $stmt = $db->prepare($sql);
                
                // Bind parameter
                $stmt->bindParam(':id', $args['id']);
                
                // Execute the query
                $stmt->execute();
                
                // Return success message
                return $response->withJson(['message' => 'booking deleted successfully']);
            });

            $app->delete('/owner/del/{id}', function ($request, $response, $args) {
                // Get the ID from the route parameters
                $ownerId = $args['id'];
                
                // Retrieve the database connection from the container
                $db = $this->get('db');
                
                // Prepare the DELETE query
                $sql = $db->prepare("DELETE FROM owners WHERE id = :id");
                
                // Bind the parameter
                $sql->bindParam(':id', $ownerId);
                
                // Execute the query
                $sql->execute();
                
                // Check if any rows were affected (if the owner with the given ID existed)
                if ($sql->rowCount() > 0) {
                    // Return success message if the owner was deleted
                    return $response->withJson(['message' => 'Owner deleted successfully'], 200);
                } else {
                    // Return error message if the owner with the given ID was not found
                    return $response->withJson(['message' => 'Owner not found'], 404);
                }
                
            });
            
