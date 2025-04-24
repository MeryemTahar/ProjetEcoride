import { bootstrapApplication } from '@angular/platform-browser';
import { AppComponent } from './app/app.component';
import { provideHttpClient, withInterceptorsFromDi, withFetch } from '@angular/common/http';
import { provideRouter } from '@angular/router';
import { routes } from './app/app.routes';
import { HTTP_INTERCEPTORS } from '@angular/common/http';
import { AuthInterceptor } from './app/services/auth/auth.interceptor';
import { importProvidersFrom } from '@angular/core';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
// Exporter une fonction nommée "bootstrap" qui retourne la promesse du bootstrapApplication
export default function bootstrap(): Promise<any> {
  return bootstrapApplication(AppComponent, {
    providers: [
      provideRouter(routes),
      provideHttpClient(withInterceptorsFromDi(), withFetch()),
      { provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true },
      importProvidersFrom(BrowserAnimationsModule), // Pour les animations Angular
    ]
  });
}

