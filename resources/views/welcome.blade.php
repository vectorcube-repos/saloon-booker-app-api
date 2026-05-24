<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SalonBooker</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              brand: {
                50: '#eef2ff',
                100: '#e0e7ff',
                500: 'hsl(4, 80%, 52%)',
                600: 'hsl(4, 80%, 52%)',
                700: 'hsl(358, 70%, 44%)'
              }
            },
            boxShadow: {
              'brand-sm': '0 8px 20px hsla(4, 80%, 52%, 0.2)',
              'brand-lg': '0 14px 35px hsla(4, 80%, 52%, 0.25)'
            }
          }
        }
      }
    </script>
    <style>
      .mobile-app-screenshot {
        background: transparent;
        border: 0;
        box-shadow: none;
        filter: drop-shadow(0 18px 24px hsla(220, 25%, 12%, 0.16));
      }
    </style>
  </head>
  <body class="min-h-screen bg-[hsl(0_0%_99%)] text-[hsl(220_25%_12%)] [font-family:'DM_Sans',system-ui,sans-serif]">
    <nav class="fixed top-0 left-0 right-0 z-50 border-b border-[hsl(220_13%_90%)]/70 bg-white/80 backdrop-blur-xl">
      <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <a href="#" class="flex items-center text-xl font-bold">
          <span>Salon</span><span class="text-brand-600">Booker</span>
        </a>

        <div class="hidden items-center gap-8 text-sm font-medium text-[hsl(220_10%_46%)] md:flex">
          <a href="#features" class="transition-colors hover:text-[hsl(220_25%_12%)]">Features</a>
          <a href="#admin-panel" class="transition-colors hover:text-[hsl(220_25%_12%)]">Admin Panel</a>
          <a href="#highlights" class="transition-colors hover:text-[hsl(220_25%_12%)]">Why SalonBooker</a>
        </div>

        <a href="https://github.com/vectorcube-repos/flutter-salon-booker-app" class="inline-flex items-center gap-2 rounded-md border border-[#1f2328] bg-[#24292f] px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors duration-200 hover:bg-[#32383f] focus:outline-none focus:ring-2 focus:ring-[#0969da] focus:ring-offset-2" target="_blank" rel="noopener noreferrer">
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 .5C5.65.5.5 5.65.5 12c0 5.09 3.29 9.4 7.86 10.93.58.11.79-.25.79-.56v-2.17c-3.2.7-3.88-1.36-3.88-1.36-.52-1.33-1.28-1.69-1.28-1.69-1.04-.71.08-.7.08-.7 1.16.08 1.77 1.19 1.77 1.19 1.03 1.76 2.69 1.25 3.35.96.1-.75.4-1.25.73-1.54-2.55-.29-5.24-1.28-5.24-5.69 0-1.26.45-2.28 1.19-3.09-.12-.29-.52-1.46.11-3.04 0 0 .98-.31 3.18 1.18.92-.26 1.91-.38 2.89-.39.98.01 1.97.13 2.89.39 2.21-1.49 3.18-1.18 3.18-1.18.63 1.58.23 2.75.11 3.04.74.81 1.19 1.83 1.19 3.09 0 4.42-2.69 5.39-5.26 5.68.41.36.78 1.06.78 2.14v3.18c0 .31.21.67.8.56A11.5 11.5 0 0 0 23.5 12C23.5 5.65 18.35.5 12 .5Z" />
          </svg>
          Source Code
        </a>
      </div>
    </nav>

    <main>
      <section class="relative flex min-h-[calc(100vh-4rem)] items-start overflow-hidden bg-[hsl(0_0%_99%)] pt-16 lg:items-center">
        <div class="relative z-10 mx-auto w-full max-w-7xl px-4 pb-6 pt-10 sm:px-6 md:pb-8 md:pt-24 lg:px-8 lg:pb-12 lg:pt-16">
          <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16 xl:gap-20">
            <div class="text-center lg:pr-8 lg:text-left xl:pr-12">
              <h1 class="mb-6 text-4xl font-extrabold leading-tight text-[hsl(220_25%_12%)] sm:text-5xl md:text-6xl">
                The Complete<br />
                <span class="bg-gradient-to-r from-brand-500 to-brand-700 bg-clip-text text-transparent">Salon Booking</span><br />
                Platform
              </h1>

              <p class="mx-auto mb-8 max-w-md text-lg leading-relaxed text-[hsl(220_10%_46%)] lg:mx-0">
                A full-featured appointment booking app for salons with a beautiful mobile experience for customers and a powerful admin panel to manage everything.
              </p>

              <div class="flex flex-col items-stretch gap-3 sm:items-center lg:items-start">
                <a href="https://www.youtube.com/watch?v=jz5L__6JGdI" class="inline-flex w-full items-center justify-center gap-2 rounded-md border border-[#b91c1c] bg-[#dc2626] px-6 py-3 text-base font-semibold text-white shadow-sm transition-colors duration-200 hover:bg-[#b91c1c] focus:outline-none focus:ring-2 focus:ring-[#dc2626] focus:ring-offset-2 sm:w-auto" target="_blank" rel="noopener noreferrer">
                  <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2 31.2 31.2 0 0 0 0 12a31.2 31.2 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1A31.2 31.2 0 0 0 24 12a31.2 31.2 0 0 0-.5-5.8ZM9.6 15.6V8.4L15.9 12l-6.3 3.6Z" />
                  </svg>
                  Watch App Demo
                </a>
                <div class="flex flex-col gap-3 sm:flex-row">
                  <a href="https://github.com/vectorcube-repos/flutter-salon-booker-app" class="inline-flex w-full items-center justify-center gap-2 rounded-md border border-[#1f2328] bg-[#24292f] px-6 py-3 text-base font-semibold text-white shadow-sm transition-colors duration-200 hover:bg-[#32383f] focus:outline-none focus:ring-2 focus:ring-[#0969da] focus:ring-offset-2 sm:w-auto" target="_blank" rel="noopener noreferrer">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M12 .5C5.65.5.5 5.65.5 12c0 5.09 3.29 9.4 7.86 10.93.58.11.79-.25.79-.56v-2.17c-3.2.7-3.88-1.36-3.88-1.36-.52-1.33-1.28-1.69-1.28-1.69-1.04-.71.08-.7.08-.7 1.16.08 1.77 1.19 1.77 1.19 1.03 1.76 2.69 1.25 3.35.96.1-.75.4-1.25.73-1.54-2.55-.29-5.24-1.28-5.24-5.69 0-1.26.45-2.28 1.19-3.09-.12-.29-.52-1.46.11-3.04 0 0 .98-.31 3.18 1.18.92-.26 1.91-.38 2.89-.39.98.01 1.97.13 2.89.39 2.21-1.49 3.18-1.18 3.18-1.18.63 1.58.23 2.75.11 3.04.74.81 1.19 1.83 1.19 3.09 0 4.42-2.69 5.39-5.26 5.68.41.36.78 1.06.78 2.14v3.18c0 .31.21.67.8.56A11.5 11.5 0 0 0 23.5 12C23.5 5.65 18.35.5 12 .5Z" />
                    </svg>
                    Flutter App
                  </a>
                  <a href="https://github.com/vectorcube-repos/saloon-booker-app-api" class="inline-flex w-full items-center justify-center gap-2 rounded-md border border-[#1f2328] bg-[#24292f] px-6 py-3 text-base font-semibold text-white shadow-sm transition-colors duration-200 hover:bg-[#32383f] focus:outline-none focus:ring-2 focus:ring-[#0969da] focus:ring-offset-2 sm:w-auto" target="_blank" rel="noopener noreferrer">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M12 .5C5.65.5.5 5.65.5 12c0 5.09 3.29 9.4 7.86 10.93.58.11.79-.25.79-.56v-2.17c-3.2.7-3.88-1.36-3.88-1.36-.52-1.33-1.28-1.69-1.28-1.69-1.04-.71.08-.7.08-.7 1.16.08 1.77 1.19 1.77 1.19 1.03 1.76 2.69 1.25 3.35.96.1-.75.4-1.25.73-1.54-2.55-.29-5.24-1.28-5.24-5.69 0-1.26.45-2.28 1.19-3.09-.12-.29-.52-1.46.11-3.04 0 0 .98-.31 3.18 1.18.92-.26 1.91-.38 2.89-.39.98.01 1.97.13 2.89.39 2.21-1.49 3.18-1.18 3.18-1.18.63 1.58.23 2.75.11 3.04.74.81 1.19 1.83 1.19 3.09 0 4.42-2.69 5.39-5.26 5.68.41.36.78 1.06.78 2.14v3.18c0 .31.21.67.8.56A11.5 11.5 0 0 0 23.5 12C23.5 5.65 18.35.5 12 .5Z" />
                    </svg>
                    Laravel API
                  </a>
                </div>
              </div>

              <div class="mt-12 flex items-center justify-center gap-8 text-[hsl(220_10%_46%)] lg:justify-start">
                <div class="text-center">
                  <p class="text-2xl font-bold text-[hsl(220_25%_12%)]">21+</p>
                  <p class="text-sm">Salons</p>
                </div>
                <div class="h-10 w-px bg-[hsl(220_13%_90%)]"></div>
                <div class="text-center">
                  <p class="text-2xl font-bold text-[hsl(220_25%_12%)]">300+</p>
                  <p class="text-sm">Appointments</p>
                </div>
                <div class="h-10 w-px bg-[hsl(220_13%_90%)]"></div>
                <div class="text-center">
                  <p class="text-2xl font-bold text-[hsl(220_25%_12%)]">155+</p>
                  <p class="text-sm">Users</p>
                </div>
              </div>
            </div>

            <div class="relative flex items-end justify-center gap-3 xl:gap-4">
              <div class="pointer-events-none absolute -bottom-16 left-1/2 w-[150%] -translate-x-1/2 xl:w-[165%]">
                <img src="/assets/screenshots/admin-dashboard.png" alt="SalonBooker Admin Dashboard" class="mobile-app-screenshot w-full opacity-40" />
              </div>

              <div class="relative z-10 hidden -mb-4 sm:block">
                <img src="/assets/screenshots/login-screen.png" alt="SalonBooker Login Screen" class="mobile-app-screenshot w-44 lg:w-52 xl:w-56" />
              </div>
              <div class="relative z-20">
                <img src="/assets/screenshots/home-screen.png" alt="SalonBooker Home Screen" class="mobile-app-screenshot w-64 lg:w-72 xl:w-80" />
              </div>
              <div class="relative z-10 hidden -mb-4 sm:block">
                <img src="/assets/screenshots/booking-screen.png" alt="SalonBooker Booking Screen" class="mobile-app-screenshot w-44 lg:w-52 xl:w-56" />
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="features" class="bg-[hsl(220_14%_97%)] py-24">
        <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="mb-16 text-center">
            <span class="text-sm font-semibold uppercase tracking-widest text-brand-600">Mobile App Features</span>
            <h2 class="mt-3 text-3xl font-bold text-[hsl(220_25%_12%)] md:text-4xl">Everything Customers Need</h2>
            <p class="mx-auto mt-3 max-w-lg text-[hsl(220_10%_46%)]">A seamless mobile experience for discovering salons, booking appointments, and managing visits.</p>
          </div>

          <div class="mb-16 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <article class="rounded-2xl border border-[hsl(220_13%_90%)] bg-white p-6 transition-all duration-300 hover:border-brand-300 hover:shadow-brand-sm">
              <h3 class="mb-1.5 text-base font-semibold text-[hsl(220_25%_12%)]">Browse Salons & Services</h3>
              <p class="text-sm leading-relaxed text-[hsl(220_10%_46%)]">Customers can explore nearby salons, filter by services, and view ratings.</p>
            </article>
            <article class="rounded-2xl border border-[hsl(220_13%_90%)] bg-white p-6 transition-all duration-300 hover:border-brand-300 hover:shadow-brand-sm">
              <h3 class="mb-1.5 text-base font-semibold text-[hsl(220_25%_12%)]">Choose Staff & Stylist</h3>
              <p class="text-sm leading-relaxed text-[hsl(220_10%_46%)]">Pick your preferred stylist and see their availability in real-time.</p>
            </article>
            <article class="rounded-2xl border border-[hsl(220_13%_90%)] bg-white p-6 transition-all duration-300 hover:border-brand-300 hover:shadow-brand-sm">
              <h3 class="mb-1.5 text-base font-semibold text-[hsl(220_25%_12%)]">Book Appointments</h3>
              <p class="text-sm leading-relaxed text-[hsl(220_10%_46%)]">Select date, time slot, and service and confirm booking in seconds.</p>
            </article>
            <article class="rounded-2xl border border-[hsl(220_13%_90%)] bg-white p-6 transition-all duration-300 hover:border-brand-300 hover:shadow-brand-sm">
              <h3 class="mb-1.5 text-base font-semibold text-[hsl(220_25%_12%)]">Booking Management</h3>
              <p class="text-sm leading-relaxed text-[hsl(220_10%_46%)]">View upcoming and past bookings with status tracking and confirmations.</p>
            </article>
            <article class="rounded-2xl border border-[hsl(220_13%_90%)] bg-white p-6 transition-all duration-300 hover:border-brand-300 hover:shadow-brand-sm">
              <h3 class="mb-1.5 text-base font-semibold text-[hsl(220_25%_12%)]">Location-Based Discovery</h3>
              <p class="text-sm leading-relaxed text-[hsl(220_10%_46%)]">Find salons near you with address, status, and service details.</p>
            </article>
            <article class="rounded-2xl border border-[hsl(220_13%_90%)] bg-white p-6 transition-all duration-300 hover:border-brand-300 hover:shadow-brand-sm">
              <h3 class="mb-1.5 text-base font-semibold text-[hsl(220_25%_12%)]">Ratings & Reviews</h3>
              <p class="text-sm leading-relaxed text-[hsl(220_10%_46%)]">Rate your experience and help others find the best salons.</p>
            </article>
          </div>

          <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="flex flex-col items-center gap-3">
              <img src="/assets/screenshots/login-screen.png" alt="Login Screen" class="mobile-app-screenshot w-full max-w-60" />
              <span class="text-sm font-medium text-[hsl(220_10%_46%)]">Login</span>
            </div>
            <div class="flex flex-col items-center gap-3">
              <img src="/assets/screenshots/otp-screen.png" alt="OTP Verification Screen" class="mobile-app-screenshot w-full max-w-60" />
              <span class="text-sm font-medium text-[hsl(220_10%_46%)]">OTP Verification</span>
            </div>
            <div class="flex flex-col items-center gap-3">
              <img src="/assets/screenshots/home-screen.png" alt="Home Screen" class="mobile-app-screenshot w-full max-w-60" />
              <span class="text-sm font-medium text-[hsl(220_10%_46%)]">Home</span>
            </div>
            <div class="flex flex-col items-center gap-3">
              <img src="/assets/screenshots/salon-list-screen.png" alt="Explore Salons" class="mobile-app-screenshot w-full max-w-60" />
              <span class="text-sm font-medium text-[hsl(220_10%_46%)]">Explore</span>
            </div>
            <div class="flex flex-col items-center gap-3">
              <img src="/assets/screenshots/salon-screen.png" alt="Salon Details" class="mobile-app-screenshot w-full max-w-60" />
              <span class="text-sm font-medium text-[hsl(220_10%_46%)]">Salon Details</span>
            </div>
            <div class="flex flex-col items-center gap-3">
              <img src="/assets/screenshots/booking-screen.png" alt="Booking Screen" class="mobile-app-screenshot w-full max-w-60" />
              <span class="text-sm font-medium text-[hsl(220_10%_46%)]">Booking</span>
            </div>
            <div class="flex flex-col items-center gap-3">
              <img src="/assets/screenshots/bookings-screen.png" alt="Bookings" class="mobile-app-screenshot w-full max-w-60" />
              <span class="text-sm font-medium text-[hsl(220_10%_46%)]">My Bookings</span>
            </div>
          </div>
        </div>
      </section>

      <section id="admin-panel" class="bg-[hsl(0_0%_99%)] py-24">
        <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="mb-16 text-center">
            <span class="text-sm font-semibold uppercase tracking-widest text-brand-600">Admin Panel</span>
            <h2 class="mt-3 text-3xl font-bold text-[hsl(220_25%_12%)] md:text-4xl">Powerful Management Dashboard</h2>
            <p class="mx-auto mt-3 max-w-lg text-[hsl(220_10%_46%)]">A comprehensive admin panel to manage salons, appointments, services, staff, and users from one place.</p>
          </div>

          <div class="mb-16 grid gap-6 text-center md:grid-cols-2 lg:grid-cols-4">
            <article class="p-6">
              <h3 class="mb-1.5 text-base font-semibold text-[hsl(220_25%_12%)]">Dashboard Overview</h3>
              <p class="text-sm leading-relaxed text-[hsl(220_10%_46%)]">Get a bird's-eye view of total salons, appointments, and users at a glance.</p>
            </article>
            <article class="p-6">
              <h3 class="mb-1.5 text-base font-semibold text-[hsl(220_25%_12%)]">Appointment Management</h3>
              <p class="text-sm leading-relaxed text-[hsl(220_10%_46%)]">View, search, filter, and manage all appointments with status tracking.</p>
            </article>
            <article class="p-6">
              <h3 class="mb-1.5 text-base font-semibold text-[hsl(220_25%_12%)]">Salon Management</h3>
              <p class="text-sm leading-relaxed text-[hsl(220_10%_46%)]">Add, edit, and manage salons with location, services, and staff details.</p>
            </article>
            <article class="p-6">
              <h3 class="mb-1.5 text-base font-semibold text-[hsl(220_25%_12%)]">User & Staff Management</h3>
              <p class="text-sm leading-relaxed text-[hsl(220_10%_46%)]">Manage customers, service providers, and their roles within the platform.</p>
            </article>
          </div>

          <div class="space-y-8">
            <div class="overflow-hidden rounded-2xl border border-[hsl(220_13%_90%)] shadow-lg">
              <img src="/assets/screenshots/admin-dashboard.png" alt="Admin Dashboard" class="w-full" />
            </div>
            <div class="overflow-hidden rounded-2xl border border-[hsl(220_13%_90%)] shadow-lg">
              <img src="/assets/screenshots/admin-appointments.png" alt="Admin Appointments" class="w-full" />
            </div>
            <div class="overflow-hidden rounded-2xl border border-[hsl(220_13%_90%)] shadow-lg">
              <img src="/assets/screenshots/admin-salons.png" alt="Admin Salons" class="w-full" />
            </div>
          </div>
        </div>
      </section>

      <section id="highlights" class="bg-[hsl(220_14%_97%)] py-24">
        <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="mb-16 text-center">
            <span class="text-sm font-semibold uppercase tracking-widest text-brand-600">Why SalonBooker</span>
            <h2 class="mt-3 text-3xl font-bold text-[hsl(220_25%_12%)] md:text-4xl">Built for Modern Salons</h2>
          </div>

          <div class="mx-auto grid max-w-5xl gap-6 md:grid-cols-2 lg:grid-cols-3">
            <article class="rounded-2xl border border-[hsl(220_13%_90%)] bg-white p-7">
              <h3 class="mb-1.5 font-semibold text-[hsl(220_25%_12%)]">Mobile-First Design</h3>
              <p class="text-sm leading-relaxed text-[hsl(220_10%_46%)]">Beautiful, intuitive UI built for iOS and Android.</p>
            </article>
            <article class="rounded-2xl border border-[hsl(220_13%_90%)] bg-white p-7">
              <h3 class="mb-1.5 font-semibold text-[hsl(220_25%_12%)]">Admin Dashboard</h3>
              <p class="text-sm leading-relaxed text-[hsl(220_10%_46%)]">Full-featured web panel for complete business management.</p>
            </article>
            <article class="rounded-2xl border border-[hsl(220_13%_90%)] bg-white p-7">
              <h3 class="mb-1.5 font-semibold text-[hsl(220_25%_12%)]">Real-Time Booking</h3>
              <p class="text-sm leading-relaxed text-[hsl(220_10%_46%)]">Instant slot availability and confirmation for customers.</p>
            </article>
            <article class="rounded-2xl border border-[hsl(220_13%_90%)] bg-white p-7">
              <h3 class="mb-1.5 font-semibold text-[hsl(220_25%_12%)]">Secure & Reliable</h3>
              <p class="text-sm leading-relaxed text-[hsl(220_10%_46%)]">OTP verification, secure data handling, and role-based access.</p>
            </article>
            <article class="rounded-2xl border border-[hsl(220_13%_90%)] bg-white p-7">
              <h3 class="mb-1.5 font-semibold text-[hsl(220_25%_12%)]">Customizable</h3>
              <p class="text-sm leading-relaxed text-[hsl(220_10%_46%)]">Adaptable to any salon brand with flexible theming.</p>
            </article>
            <article class="rounded-2xl border border-[hsl(220_13%_90%)] bg-white p-7">
              <h3 class="mb-1.5 font-semibold text-[hsl(220_25%_12%)]">Scalable Backend</h3>
              <p class="text-sm leading-relaxed text-[hsl(220_10%_46%)]">Handles multiple salons, staff, services, and thousands of bookings.</p>
            </article>
          </div>
        </div>
      </section>
    </main>

    <footer class="border-t border-[hsl(220_13%_90%)] bg-[hsl(0_0%_99%)] py-12">
      <div class="mx-auto flex w-full max-w-7xl flex-col items-center justify-between gap-6 px-4 sm:px-6 md:flex-row lg:px-8">
        <a href="#" class="flex items-center text-lg font-bold">
          <span>Salon<span class="text-brand-600">Booker</span></span>
        </a>
        <p class="text-sm text-[hsl(220_10%_46%)]">© 2026 SalonBooker. A complete salon appointment booking platform.</p>
        <div class="flex gap-6 text-sm text-[hsl(220_10%_46%)]">
          <a href="#" class="transition-colors hover:text-[hsl(220_25%_12%)]">Privacy</a>
          <a href="#" class="transition-colors hover:text-[hsl(220_25%_12%)]">Terms</a>
          <a href="#" class="transition-colors hover:text-[hsl(220_25%_12%)]">Contact</a>
        </div>
      </div>
    </footer>
  </body>
</html>
