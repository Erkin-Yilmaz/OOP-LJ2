<?php
abstract class Person {
    private string $name;
    private string $role;

    public function __construct(string $name, string $role) {
        $this->name = $name;
        $this->role = $role;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function setRole(string $role): void {
        $this->role = $role;
    }

   
    abstract public function getRoleInfo(): string;
}



class Patient extends Person {
    private float $payment;

    public function __construct(string $name, float $payment) {
        parent::__construct($name, "Patient");
        $this->payment = $payment;
    }

    public function getPayment(): float {
        return $this->payment;
    }

    public function setPayment(float $payment): void {
        $this->payment = $payment;
    }

    public function getRoleInfo(): string {
        return "Patient betaalt €" . number_format($this->payment, 2);
    }
}


abstract class Staff extends Person {
    protected float $salary;

    public function __construct(string $name, string $role, float $salary = 0) {
        parent::__construct($name, $role);
        $this->salary = $salary;
    }

    public function getSalary(): float {
        return $this->salary;
    }

    public function setSalary(float $salary): void {
        $this->salary = $salary;
    }

    // Abstracte methode om salaris te berekenen
    abstract public function calculateSalary(): float;
}


class Doctor extends Staff {
    private float $hourlyRate;
    private int $appointmentsCount = 0;

    public function __construct(string $name, float $hourlyRate) {
        parent::__construct($name, "Doctor");
        $this->hourlyRate = $hourlyRate;
    }

    public function addAppointment(): void {
        $this->appointmentsCount++;
    }

    public function calculateSalary(): float {
        $this->salary = $this->appointmentsCount * $this->hourlyRate;
        return $this->salary;
    }

    public function getRoleInfo(): string {
        return "Doctor - €" . number_format($this->salary, 2);
    }
}

class Nurse extends Staff {
    private float $fixedSalary;
    private float $bonusPerAppointment;
    private int $appointmentsCount = 0;

    public function __construct(string $name, float $fixedSalary, float $bonusPerAppointment) {
        parent::__construct($name, "Nurse");
        $this->fixedSalary = $fixedSalary;
        $this->bonusPerAppointment = $bonusPerAppointment;
    }

    public function addAppointment(): void {
        $this->appointmentsCount++;
    }

    public function calculateSalary(): float {
        $this->salary = $this->fixedSalary + ($this->appointmentsCount * $this->bonusPerAppointment);
        return $this->salary;
    }

    public function getRoleInfo(): string {
        return "Nurse - €" . number_format($this->salary, 2);
    }
}



class Appointment {
    private Patient $patient;
    private Doctor $doctor;
    private array $nurses = [];
    private DateTime $beginTime;
    private DateTime $endTime;

    public function __construct(Patient $patient, Doctor $doctor, DateTime $beginTime, DateTime $endTime) {
        $this->patient = $patient;
        $this->doctor = $doctor;
        $this->beginTime = $beginTime;
        $this->endTime = $endTime;

       
        $doctor->addAppointment();
    }

    public function addNurse(Nurse $nurse): void {
        $this->nurses[] = $nurse;
        $nurse->addAppointment();
    }

    public function getDurationHours(): float {
        $interval = $this->beginTime->diff($this->endTime);
        return ($interval->h + $interval->i / 60);
    }

    public function getDoctor(): Doctor {
        return $this->doctor;
    }

    public function getPatient(): Patient {
        return $this->patient;
    }

    public function getNurses(): array {
        return $this->nurses;
    }
}


$patient1 = new Patient("Jan Jansen", 75.00);
$patient2 = new Patient("Lisa de Vries", 60.00);


$doctor1 = new Doctor("Dr. Peters", 120.00);
$nurse1 = new Nurse("Sophie", 2000.00, 25.00);


$appointment1 = new Appointment($patient1, $doctor1, new DateTime("2025-10-15 09:00"), new DateTime("2025-10-15 10:00"));
$appointment1->addNurse($nurse1);

$appointment2 = new Appointment($patient2, $doctor1, new DateTime("2025-10-15 11:00"), new DateTime("2025-10-15 12:30"));

$doctor1->calculateSalary();
$nurse1->calculateSalary();

echo "<h2>Overzicht ziekenhuis</h2>";

echo "<strong>Dokter:</strong> " . $doctor1->getName() . "<br>";
echo "Rol: " . $doctor1->getRoleInfo() . "<br><br>";

echo "<strong>Assistente:</strong> " . $nurse1->getName() . "<br>";
echo "Rol: " . $nurse1->getRoleInfo() . "<br><br>";

echo "<strong>Patiënten:</strong><br>";
echo $patient1->getName() . " - " . $patient1->getRoleInfo() . "<br>";
echo $patient2->getName() . " - " . $patient2->getRoleInfo() . "<br><br>";

echo "<strong>Afspraken:</strong><br>";
echo $patient1->getName() . " met " . $doctor1->getName() . " (" . $appointment1->getDurationHours() . " uur)<br>";
echo $patient2->getName() . " met " . $doctor1->getName() . " (" . $appointment2->getDurationHours() . " uur)<br>";
?>
