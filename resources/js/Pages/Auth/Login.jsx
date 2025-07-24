import React from 'react';
import AuthLayout from '../../Layouts/AuthLayout';
import LoginForm from '../../Components/LoginForm';

export default function Login({ errors, flash }) {
    return (
        <AuthLayout
            title="Welcome Back"
            subtitle="Sign in to your account"
        >
            <LoginForm errors={errors} flash={flash} />
        </AuthLayout>
    );
}
