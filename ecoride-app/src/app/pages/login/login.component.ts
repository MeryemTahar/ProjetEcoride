import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { AuthService } from '../../services/auth/auth.service';
import { Router } from '@angular/router';

@Component({
  standalone: true,
  imports: [CommonModule, FormsModule],
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent {
  email: string = '';
  password: string = '';

  constructor(
    private authService: AuthService,
    private router:Router 
  ){}


  onLogin() {
    console.log ('Tentative de connexion avec:', this.email, this.password);
    this.authService.login(this.email, this.password).subscribe({
      next: response => {
        console.log('Login response:', response);
        if (response.status === 'success') {
          this.router.navigate(['/profile']);
          console.log ("Navigation effectuée");
        } else {
          alert('Identifiants incorrects');
        }
      },
      error: err => {
        console.error('Login error:', err);
      },
      complete: () => {
        console.log('Login request completed');
      }
      
    });
  }
}

