import { Routes } from '@angular/router';
import { HomeComponent } from './pages/home/home.component';
import { LoginComponent } from './pages/login/login.component';
import { PaymentComponent } from './pages/payment/payment.component';
import { RegisterComponent } from './pages/register/register.component';
import { ProfileComponent } from './pages/profile/profile.component';
import { PublishRideComponent } from './pages/publish-ride/publish-ride.component';
import { SearchRideComponent } from './pages/search-ride/search-ride.component';

// Importe la fonction guard (CanActivateFn)
import { authGuardFn } from './services/auth/auth.guard'; 

export const routes: Routes = [
  { path: '', component: HomeComponent },
  { path: 'login', component: LoginComponent },
  { path: 'register', component: RegisterComponent },

  // Routes protégées par le guard
  { path: 'payment', component: PaymentComponent, canActivate: [authGuardFn] },
  { path: 'profile', component: ProfileComponent, canActivate: [authGuardFn] },
  { path: 'publish-ride', component: PublishRideComponent, canActivate: [authGuardFn] },
  { path: 'search-ride', component: SearchRideComponent, canActivate: [authGuardFn] },
];