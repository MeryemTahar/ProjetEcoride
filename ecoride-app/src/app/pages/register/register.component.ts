import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-register',
  standalone: true,
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss'],
  imports: [FormsModule] // Ajout du FormsModule ici
})
export class RegisterComponent {
  firstname: string = '';
  lastname: string = '';
  email: string = '';
  password: string = '';
  acceptTerms: boolean = false;

  onRegister() {
    console.log('Inscription :', {
      firstname: this.firstname,
      lastname: this.lastname,
      email: this.email,
      password: this.password,
      acceptTerms: this.acceptTerms

      
    });
    // Appel à un service d'inscription ici...
  }
}
