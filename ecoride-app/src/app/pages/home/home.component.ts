import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-home',
  standalone: true,
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss'],
  imports: [FormsModule]
})
export class HomeComponent {
  departure: string = '';
  destination: string = '';
  travelDate: string = '';
  passengers: number = 1;

  resetField(field: keyof HomeComponent) {
    if (field === 'departure' || field === 'destination' || field === 'passengers') {
      (this[field] as string) = ''; // Ajout d'une assertion de type
    } else if (field === 'travelDate') {
      this.travelDate = ''; // Réinitialise la date
    }
  }
  
  resetAllFields() {
    this.departure = '';
    this.destination = '';
    this.travelDate = '';
    this.passengers = 1;
  }
}

