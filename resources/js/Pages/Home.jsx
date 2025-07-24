import React from 'react';
import AuthLayout from '../Layouts/AuthLayout';
import LoginForm from '../Components/LoginForm';

export default function Home({ errors, flash }) {
    return (
        <AuthLayout
            title="Welcome to DDO"
            subtitle="Sign in to your account"
        >
            <LoginForm errors={errors} flash={flash} />
        </AuthLayout>
    );
}
