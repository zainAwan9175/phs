<?php
// Seeder that creates the database (if missing), runs schema and seeds roles, admin, labs and equipment.

$config = require __DIR__ . '/../config.php';

// connect to MySQL server WITHOUT selecting a database so we can create the DB if it doesn't exist
try {
    $port = $config['db_port'] ?? 3306;
    $tmpDsn = "mysql:host={$config['db_host']};port={$port};charset=utf8mb4";
    $tmpPdo = new PDO($tmpDsn, $config['db_user'], $config['db_pass'], [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    echo "Could not connect to MySQL server: " . htmlspecialchars($e->getMessage()) . "\n";
    exit;
}

$schema = file_get_contents(__DIR__ . '/schema.sql');
// split into before USE and after USE
$parts = explode("USE `" . ($config['db_name'] ?? 'smartlab') . "`;", $schema, 2);
$beforeUse = $parts[0] ?? '';
$afterUse = $parts[1] ?? '';

try {
    if (trim($beforeUse) !== '') {
        $tmpPdo->exec($beforeUse);
        echo "Database created/ensured.\n";
    }
} catch (Exception $e) {
    echo "Error creating database: " . $e->getMessage() . "\n";
    // continue to try executing remaining statements
}

// now connect to the target database
try {
    $dsn = "mysql:host={$config['db_host']};port={$port};dbname={$config['db_name']};charset=utf8mb4";
    $pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
} catch (Exception $e) {
    echo "Failed to connect to the newly created database: " . htmlspecialchars($e->getMessage()) . "\n";
    exit;
}

// execute remaining DDL (tables)
try {
    if (trim($afterUse) !== '') {
        $pdo->exec($afterUse);
        echo "Schema executed\n";
    }
} catch (Exception $e) {
    echo "Schema error: " . $e->getMessage() . "\n";
}

// Insert roles
$roles = ['student','lab_assistant','lab_manager','admin'];
$stmt = $pdo->prepare('INSERT IGNORE INTO roles (name) VALUES (:name)');
foreach ($roles as $r) $stmt->execute(['name'=>$r]);

// create admin user if not exists
$adminEmail = 'admin@smartlab.local';
$check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$check->execute([$adminEmail]);
if (!$check->fetch()) {
    $roleId = $pdo->query("SELECT id FROM roles WHERE name='admin'")->fetchColumn();
    $pass = password_hash('admin123', PASSWORD_BCRYPT);
    $ins = $pdo->prepare('INSERT INTO users (role_id, first_name, last_name, email, password_hash) VALUES (?,?,?,?,?)');
    $ins->execute([$roleId, 'Admin', 'User', $adminEmail, $pass]);
    echo "Admin user created: {$adminEmail} / admin123\n";
}

// Seed labs
$labs = [
    ['Electronics Lab','Room 101','Electronics equipment and benches'],
    ['Chemistry Lab','Room 102','Chemistry glassware and reagents'],
    ['Physics Lab','Room 103','Physics apparatus'],
    ['Civil Engineering Lab','Room 104','Civil tools and surveying'],
    ['Multimedia Lab','Room 105','Cameras, lighting and audio'],
    ['Mechanical Lab','Room 106','Mechanical workshop and machines'],
];
$insLab = $pdo->prepare('INSERT IGNORE INTO labs (name,location,description) VALUES (?,?,?)');
foreach ($labs as $l) $insLab->execute($l);

// Seed full equipment lists based on proposal
$equipmentLists = [
    'Electronics Lab' => [
        ['Oscilloscope','Signal observation','OSC-01'],
        ['Function generator','Signal generation','GEN-02'],
        ['Digital multimeter','Voltage/Current/Resistance measurement','DMM-01'],
        ['Breadboard','Circuit prototyping','BRD-01'],
        ['Arduino board','Microcontroller programming','ARD-01'],
        ['Raspberry Pi','Embedded systems development','RPI-01'],
        ['Soldering iron','Soldering components','SLD-01'],
        ['Power supply unit','Voltage source for circuit','PSU-01'],
        ['Logic analyzer','Digital signal testing','LA-01'],
        ['Capacitors & Resistors kit','Basic circuit components','KIT-01'],
        ['Wire strippers','For stripping wires','WS-01'],
        ['Jumper wires','For connections on breadboards','JW-01'],
        ['ICs','Circuit chips','IC-01'],
        ['Transistors','Circuit components','TR-01'],
        ['LEDs','Indicator lights','LED-01'],
        ['PCB Board','Printed circuit board','PCB-01'],
        ['Signal probes','For oscilloscope connections','SP-01'],
        ['Safety goggles','Safety in handling electronics','SG-01'],
        ['Multicore cables','Circuit wiring','MC-01'],
        ['De-soldering pump','For removing solder','DSP-01']
    ],
    'Chemistry Lab' => [
        ['Beaker','Liquid holding/mixing','CH-01'],
        ['Test tube','Chemical reactions','CH-02'],
        ['Test tube rack','Holding test tubes','CH-03'],
        ['Burette','Titration equipment','CH-04'],
        ['Pipette','Measuring liquids','CH-05'],
        ['Conical flask','Chemical reactions','CH-06'],
        ['Funnel','Pouring liquids','CH-07'],
        ['Measuring Cylinder','Measuring volume','CH-08'],
        ['Hot plate','Heating chemicals','CH-09'],
        ['Bunsen burner','Heating source','CH-10'],
        ['Glass rod','Stirring liquids','CH-11'],
        ['Safety goggles','Eye protection','CH-12'],
        ['Gloves','Hands protection','CH-13'],
        ['Clamp stand','Holds glassware','CH-14'],
        ['Fume hood','Ventilation for chemicals','CH-15'],
        ['pH meter','Acidity measurement','CH-16'],
        ['Dropper','Small quantity transfer','CH-17'],
        ['Crucible tongs','Holding hot items','CH-18'],
        ['Evaporating dish','Evaporation of solutions','CH-19'],
        ['Chemical reagents','Used in experiments','CH-20']
    ],
    'Physics Lab' => [
        ['Vernier Calliper','Length measurement','PH-01'],
        ['Screw gauge','Small object measurement','PH-02'],
        ['Stopwatch','Time measurement','PH-03'],
        ['Pendulum bob','Simple harmonic motion','PH-04'],
        ['Light box','Light experiment','PH-05'],
        ['Lens & mirror set','Optics experiments','PH-06'],
        ['Prism','Light dispersion','PH-07'],
        ['Telescope','Astronomy experiments','PH-08'],
        ['Resistor coil','Ohm’s law experiments','PH-09'],
        ['Ammeter','Current measurement','PH-10'],
        ['Voltmeter','Voltage measurement','PH-11'],
        ['Galvanometer','Electrical current detection','PH-12'],
        ['Rheostat','Resistance adjustment','PH-13'],
        ['Inclined plane','Motion experiments','PH-14'],
        ['Ticker timer','Motion tracking','PH-15'],
        ['Spring balance','Force measurement','PH-16'],
        ['Compass','Magnetic field direction','PH-17'],
        ['Bar magnet','Magnetism','PH-18'],
        ['Circuit wires','Electrical circuits','PH-19'],
        ['Multimeter','Electric readings','PH-20']
    ],
    'Civil Engineering Lab' => [
        ['Total station','Surveying and mapping','CE-01'],
        ['Auto level','Elevation measurement','CE-02'],
        ['Theodolite','Measures angles in surveying','CE-03'],
        ['Measuring tape','Distance measurement','CE-04'],
        ['Surveying chain','Traditional land measurement','CE-05'],
        ['Plumb bob','Checking vertical alignment','CE-06'],
        ['Spirit level','Ensures surfaces are level','CE-07'],
        ['Hand auger','Collecting soil samples','CE-08'],
        ['Soil core sampler','Extract intact soil cores','CE-09'],
        ['CBR Mould & Hammer','Field soil compaction testing','CE-10'],
        ['Sieves & sieve shaker','Particle size analysis','CE-11'],
        ['Curing tank','Concrete sample curing','CE-12'],
        ['Slump cone set','Testing workability of concrete','CE-13'],
        ['Cylindrical moulds','Casting concrete test specimens','CE-14'],
        ['Stopwatch','Time based testing','CE-15'],
        ['Thermometer','Measuring temperature','CE-16'],
        ['Sample bags & tags','Storing and labelling soil','CE-17'],
        ['Field data log sheet','Provided with tools for experiments','CE-18'],
        ['Personal safety gear','Safety equipment','CE-19'],
        ['Drafting tools','Civil drawing instruments','CE-20']
    ],
    'Multimedia Lab' => [
        ['DSLR Camera','Photography and videography','MM-01'],
        ['Green Screen kit','Chroma key effects','MM-02'],
        ['Studio Lightning setup','Proper lighting for video shoots','MM-03'],
        ['Tripod','Camera stability stand','MM-04'],
        ['Audio recording mic','High quality audio input','MM-05'],
        ['Headphones','Audio monitoring','MM-06'],
        ['External hard Drive','File backup and storage','MM-07'],
        ['3D printer','Prototyping and modelling','MM-08'],
        ['Projector','Demo and presentation setup','MM-09'],
        ['VR headset','Immersive animation experience','MM-10'],
        ['Storyboarding tools','Planning animations','MM-11'],
        ['USB Mic','Voice-over recording','MM-12'],
        ['Animation books & guides','Reference and learning','MM-13'],
        ['Ring Light','Portable lighting','MM-14'],
        ['Drawing tablet','Digital sketching','MM-15'],
        ['Gimbal Stabilizer','Stabilizing camera motion','MM-16'],
        ['Bluetooth speaker','Testing sound output','MM-17'],
        ['Lens kit','Swappable lenses for DSLR','MM-18'],
        ['Memory cards','Transferring media files','MM-19'],
        ['Backdrop cloths & props','Creative and scripted shoots','MM-20']
    ],
    'Mechanical Lab' => [
        ['Lathe machine','Shaping metals','ME-01'],
        ['Milling machine','Material removal','ME-02'],
        ['Drilling machine','Hole making','ME-03'],
        ['CNC machine','Computer controlled machine','ME-04'],
        ['Torque wrench','Tightening bolts','ME-05'],
        ['Micrometer','Internal & external measurement','ME-06'],
        ['Welding machine','Metal joining','ME-07'],
        ['Hydraulic jack','Lifting heavy loads','ME-08'],
        ['Pneumatic kit','Air pressure experiments','ME-09'],
        ['Gearbox','Transmission study','ME-10'],
        ['Bearings','Rotational elements','ME-11'],
        ['Anvil','Forging surface','ME-12'],
        ['Tool kit','Hand tools','ME-13'],
        ['Sandpaper','Surface smoothing','ME-14'],
        ['Chisel set','Shaping materials','ME-15'],
        ['Hammer','Basic mechanical tasks','ME-16'],
        ['Feeler gauge','Measures gap width','ME-17'],
        ['Allen key set','Hex socket screw','ME-18'],
        ['Spanner set','Tightening/loosening nuts and bolts','ME-19'],
        ['Hacksaw','Cutting metal or plastics','ME-20']
    ]
];

$insEq = $pdo->prepare('INSERT IGNORE INTO equipment (lab_id,name,category,asset_tag) VALUES (?,?,?,?)');
foreach ($equipmentLists as $labName => $items) {
    $labId = $pdo->query("SELECT id FROM labs WHERE name='".addslashes($labName)."'")->fetchColumn();
    if (!$labId) continue;
    foreach ($items as $it) {
        $insEq->execute([$labId, $it[0], 'default', $it[2]]);
    }
}

echo "Seeding complete.\n";
foreach ($equipmentLists as $labName => $items) {
    $labId = $pdo->query("SELECT id FROM labs WHERE name='".addslashes($labName)."'")->fetchColumn();
    if (!$labId) continue;
    foreach ($items as $it) {
        $insEq->execute([$labId, $it[0], 'default', $it[2]]);
    }
}

echo "Seeding complete.\n";
