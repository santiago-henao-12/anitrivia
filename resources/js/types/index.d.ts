export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
    two_factor_secret: ?string;
    two_factor_recovery_codes: ?string;
    two_factor_confirmed_at: ?string;
    created_at: string;
    updated_at: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
        password_confirmed_at: string;
    };
};
