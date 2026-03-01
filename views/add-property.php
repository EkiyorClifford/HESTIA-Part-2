<?php
require_once '../classes/State.php';

$state = new State();
$states = $state->get_states();
$lglist = '<option value="" selected disabled>— select LGA —</option>';
$ptypes = $state->get_property_types();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property | HESTIA</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --hestia-hearth: #C44536;
            --hestia-ember: #8C3E2C;
            --hestia-flame: #E67E51;
            --hestia-plum-dark: #1A0F1E;
            --hestia-plum-mid: #5A2E55;
            --hestia-stone-light: #FAF8F5;
            --hestia-stone-warm: #F0EDE9;
            --hestia-text-dark: #2C2C2C;
            --hestia-text-light: #666;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--hestia-stone-light);
            color: var(--hestia-text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navigation - refined with HESTIA palette */
        .navbar {
            background: var(--hestia-plum-dark) !important;
            box-shadow: 0 4px 20px rgba(26, 15, 30, 0.2);
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .navbar-brand {
            font-weight: 300;
            color: var(--hestia-stone-light) !important;
            font-size: 1.8rem;
            letter-spacing: 3px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .navbar-nav .nav-link {
            color: var(--hestia-stone-light) !important;
            font-weight: 300;
            font-size: 0.9rem;
            letter-spacing: 0.02em;
            margin: 0 5px;
            padding: 0.5rem 1rem !important;
            transition: color 0.2s;
        }
        
        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--hestia-flame) !important;
        }

        /* Main form container - elegant card */
        .form-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 45px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 30px 60px -30px rgba(26, 15, 30, 0.25);
            border: 1px solid rgba(197, 69, 54, 0.1);
        }
        
        .form-container h2 {
            font-weight: 300;
            color: var(--hestia-plum-dark);
            margin-bottom: 0.5rem;
            text-align: center;
            font-size: 2.2rem;
            letter-spacing: -0.02em;
        }
        
        .form-subtitle {
            text-align: center;
            color: var(--hestia-text-light);
            font-weight: 300;
            font-size: 0.9rem;
            margin-bottom: 2.5rem;
            border-bottom: 1px solid var(--hestia-stone-warm);
            padding-bottom: 1.5rem;
        }

        /* Form elements - minimalist but warm */
        .form-label {
            font-weight: 500;
            color: var(--hestia-text-dark);
            margin-bottom: 0.5rem;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .form-control, .form-select {
            border: 1.5px solid var(--hestia-stone-warm);
            border-radius: 16px;
            padding: 0.9rem 1.2rem;
            font-size: 0.95rem;
            font-weight: 300;
            transition: all 0.2s;
            background: white;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--hestia-hearth);
            box-shadow: 0 0 0 3px rgba(197, 69, 54, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #ccc;
            font-weight: 300;
        }

        /* Section dividers */
        .section-title {
            font-size: 1.1rem;
            font-weight: 400;
            color: var(--hestia-plum-mid);
            margin: 2rem 0 1.5rem 0;
            border-left: 4px solid var(--hestia-flame);
            padding-left: 1rem;
        }
        
        .section-title:first-of-type {
            margin-top: 0;
        }

        /* Amenities grid */
        .amenities-checkboxes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 1rem;
            background: var(--hestia-stone-light);
            padding: 1.5rem;
            border-radius: 20px;
        }

        .form-check {
            padding: 0.5rem;
            margin: 0;
            border-radius: 40px;
            transition: background 0.2s;
        }

        .form-check:hover {
            background: rgba(230, 126, 81, 0.05);
        }

        .form-check-input {
            border: 2px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            width: 1.2rem;
            height: 1.2rem;
            margin-top: 0.15rem;
        }

        .form-check-input:checked {
            background-color: var(--hestia-hearth);
            border-color: var(--hestia-hearth);
        }

        .form-check-label {
            margin-left: 0.5rem;
            cursor: pointer;
            font-weight: 400;
            color: var(--hestia-text-dark);
            font-size: 0.95rem;
        }

        /* Button - HESTIA signature */
        .btn-primary {
            background: var(--hestia-hearth);
            border: none;
            padding: 1.2rem;
            font-weight: 400;
            transition: all 0.3s;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.9rem;
            margin-top: 1.5rem;
            color: white;
        }

        .btn-primary:hover {
            background: var(--hestia-ember);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -8px var(--hestia-hearth);
        }

        /* File input styling */
        input[type="file"] {
            padding: 0.7rem 1.2rem;
        }
        
        input[type="file"]::file-selector-button {
            background: var(--hestia-stone-warm);
            border: none;
            border-radius: 40px;
            padding: 0.5rem 1.5rem;
            margin-right: 1rem;
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            color: var(--hestia-text-dark);
            transition: all 0.2s;
        }
        
        input[type="file"]::file-selector-button:hover {
            background: var(--hestia-flame);
            color: white;
        }

        /* Footer */
        .footer {
            background: var(--hestia-plum-dark);
            color: var(--hestia-stone-light);
            padding: 40px 0 20px;
            margin-top: auto;
            border-top: 1px solid var(--hestia-plum-mid);
        }
        
        .footer p {
            font-weight: 300;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        main {
            flex-grow: 1;
        }

        /* Required field indicator */
        .required::after {
            content: "*";
            color: var(--hestia-hearth);
            margin-left: 4px;
            font-size: 1.1rem;
        }

        /* Status badge */
        .status-badge {
            background: var(--hestia-stone-warm);
            padding: 0.5rem 1rem;
            border-radius: 40px;
            font-size: 0.8rem;
            display: inline-block;
            color: var(--hestia-text-dark);
        }
    </style>
</head>
<body>
    <!-- Header with Navigation - HESTIA style -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">HESTIA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="properties.php">Properties</a></li>
                    <li class="nav-item"><a class="nav-link active" href="add-property.php">Add Property</a></li>
                    <li class="nav-item"><a class="nav-link" href="landlord-dashboard.php">Dashboard</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container" style="margin-top: 100px;">
        <div class="form-container">
            <h2>Add property</h2>
            <div class="form-subtitle">List your space with HESTIA</div>
            
            <form action="process/process_add_property.php" method="POST" enctype="multipart/form-data">
                <!-- Basic Information Section -->
                <div class="section-title">Basic Information</div>
                
                <!-- Title (from DB: title) -->
                <div class="mb-4">
                    <label for="title" class="form-label required">Property Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="e.g., Cozy 2-Bedroom Apartment" required>
                </div>
                
                <!-- Description (from DB: description) -->
                <div class="mb-4">
                    <label for="description" class="form-label required">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe your property..." required></textarea>
                </div>
                
                <div class="row">
                    <!-- Property Type (from DB: property_type_id - would need to fetch from types table) -->
                    <div class="col-md-6 mb-4">
                        <label for="property_type_id" class="form-label required">Property Type</label>
                        <select class="form-select" id="property_type_id" name="property_type_id" required>
                            <option value="" selected disabled>— select type —</option>
                            <?php foreach ($ptypes as $ptype) { ?>
                                <option value="<?php echo $ptype['type_id']; ?>"><?php echo $ptype['type_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <!-- Listing Type (from DB: listing_type - rent/sale) -->
                    <div class="col-md-6 mb-4">
                        <label for="listing_type" class="form-label required">Listing Type</label>
                        <select class="form-select" id="listing_type" name="listing_type" required>
                            <option value="" selected disabled>— select —</option>
                            <option value="rent">For Rent</option>
                            <option value="sale">For Sale</option>
                        </select>
                    </div>
                </div>
                
                <!-- Price (from DB: amount) -->
                <div class="mb-4">
                    <label for="amount" class="form-label required">Price</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-1 border-end-0 rounded-start-16" style="border-color: var(--hestia-stone-warm);">₦</span>
                        <input type="number" step="0.01" class="form-control rounded-start-0" id="amount" name="amount" placeholder="250,000" required>
                    </div>
                </div>
                
                <!-- Location Section -->
                <div class="section-title">Location</div>
                
                <div class="row">
                    <!-- State (from DB: state_id) -->
                    <div class="col-md-6 mb-4">
                        <label for="state_id" class="form-label required">State</label>
                        <select class="form-select" id="state_id" name="state_id" required>
                            <option value="" selected disabled>— select state —</option>
                            <?php foreach($states as $state){ ?>
                                <option value="<?php echo $state['state_id']; ?>"><?php echo $state['state_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <!-- LGA (from DB: lga_id) -->
                    <div class="col-md-6 mb-4">
                        <label for="lga_id" class="form-label required">Local Government</label>
                        <select class="form-select" id="lga_id" name="lga_id" required>
                            <?php echo $lglist; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Property Address (from DB: prop_address) -->
                <div class="mb-4">
                    <label for="prop_address" class="form-label required">Street Address</label>
                    <input type="text" class="form-control" id="prop_address" name="prop_address" placeholder="123, Mind Your Biz Street" required>
                </div>
                
                <!-- Property Details Section -->
                <div class="section-title">Property Details</div>
                
                <div class="row">
                    <!-- Bedrooms (from DB: bedroom) -->
                    <div class="col-md-4 mb-4">
                        <label for="bedroom" class="form-label required">Bedrooms</label>
                        <input type="number" class="form-control" id="bedroom" name="bedroom" placeholder="2" min="0" required>
                    </div>
                    
                    <!-- Furnished (from DB: furnished) -->
                    <div class="col-md-4 mb-4">
                        <label for="furnished" class="form-label required">Furnished</label>
                        <select class="form-select" id="furnished" name="furnished" required>
                            <option value="" selected disabled>— select —</option>
                            <option value="Furnished">Furnished</option>
                            <option value="Unfurnished">Unfurnished</option>
                        </select>
                    </div>
                    
                    <!-- Status (from DB: status - default 'available') -->
                    <div class="col-md-4 mb-4">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="available" selected>Available</option>
                        </select>
                        <small class="text-muted">Default: available</small>
                    </div>
                </div>
                
                <!-- Images Section -->
                <div class="section-title">Media</div>
                
                <div class="mb-4">
                    <label for="images" class="form-label">Property Images</label>
                    <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                    <small class="form-text text-muted">Upload multiple images (JPEG, PNG, JPG)</small>
                </div>
                
                <!-- Amenities would be in a seperate table -->
                <div class="mb-4">
                    <label class="form-label">Amenities</label>
                    <div class="amenities-checkboxes">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="parking" name="amenities[]" value="parking">
                            <label class="form-check-label" for="parking">Parking</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="pets" name="amenities[]" value="pets">
                            <label class="form-check-label" for="pets">Pet Friendly</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="laundry" name="amenities[]" value="laundry">
                            <label class="form-check-label" for="laundry">Laundry</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="gym" name="amenities[]" value="gym">
                            <label class="form-check-label" for="gym">Gym</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="pool" name="amenities[]" value="pool">
                            <label class="form-check-label" for="pool">Pool</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="balcony" name="amenities[]" value="balcony">
                            <label class="form-check-label" for="balcony">Balcony</label>
                        </div>
                    </div>
                </div>
                
                <!-- Hidden fields -->
                <input type="hidden" name="user_id" value="1"> <!-- Would be from session -->
                <input type="hidden" name="created_at" value="<?php echo date('Y-m-d H:i:s'); ?>">
                
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100" name="add_property">List property →</button>
                
                <div class="text-center mt-4">
                    <span class="status-badge"><i class="far fa-clock me-1"></i> Listing will be reviewed</span>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <p>© 2026 HESTIA Property Rentals. All rights reserved.</p>
            <p class="small opacity-50">est. with warmth in Lagos</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#state_id").change(function(){
                var selectedstate = $(this).val();
                if(selectedstate) {
                    $.ajax({
                        url: "../process/process_lga.php",
                        type: "POST",
                        data: {state_id: selectedstate},
                        success: function(response) {
                            $("#lga_id").html(response);
                        },
                        error: function() {
                            alert("Error loading LGAs");
                        }
                    });
                } else {
                    $("#lga_id").html('<option value="" selected disabled>— select LGA —</option>');
                }
            });
        });
    </script>
</body>
</html>