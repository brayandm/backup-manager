"use client";

import {
  Box,
  TextField,
  Button,
  Alert,
  FormControl,
  InputLabel,
  OutlinedInput,
  InputAdornment,
  IconButton,
} from "@mui/material";
import React, { FormEvent, useState } from "react";
import { signIn } from "next-auth/react";
import { useSearchParams } from "next/navigation";
import { Visibility, VisibilityOff } from "@mui/icons-material";
import { useRouter } from "next/navigation";
import axios from "axios";
import theme from "@/lib/theme";

const RegisterForm: React.FC = () => {
  const [fullname, setFullname] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [registratioError, setRegistratioError] = useState(false);
  const [signInError, setSignInError] = useState(false);
  const [timerId, setTimerId] = useState<NodeJS.Timeout | undefined>(undefined);
  const searchParams = useSearchParams();
  const router = useRouter();

  const [showPassword, setShowPassword] = React.useState(false);

  const handleClickShowPassword = () => setShowPassword((show) => !show);

  const handleMouseDownPassword = (
    event: React.MouseEvent<HTMLButtonElement>
  ) => {
    event.preventDefault();
  };

  const handleSubmit = async (event: FormEvent) => {
    event.preventDefault();

    const resRegistration = await axios.post("/api/register", {
      name: fullname,
      email: email,
      password: password,
    });

    if (resRegistration) {
      if (resRegistration.status !== 200) {
        clearTimeout(timerId);
        setRegistratioError(true);
        setTimerId(setTimeout(() => setRegistratioError(false), 2000));
        return;
      }
    }

    const resSignIn = await signIn("credentials", {
      email: email,
      password: password,
      redirect: false,
    });

    if (resSignIn) {
      if (resSignIn.status !== 200) {
        clearTimeout(timerId);
        setSignInError(true);
        setTimerId(setTimeout(() => setSignInError(false), 2000));
        return;
      }
    }

    const callbackUrl = searchParams.get("callbackUrl") || "/dashboard";

    router.push(callbackUrl);
  };

  return (
    <div
      style={{
        display: "flex",
        flexDirection: "column",
        alignItems: "center",
      }}
    >
      <h1
        style={{
          textAlign: "center",
          marginTop: "50px",
          marginBottom: "0px",
          fontSize: "40px",
          color: theme.palette.primary.main,
        }}
      >
        Backup Manager
      </h1>
      <h2
        style={{
          marginTop: "50px",
          marginBottom: "0px",
        }}
      >
        Create an account
      </h2>
      <Box
        component="form"
        sx={{
          display: "flex",
          flexDirection: "column",
          maxWidth: "300px",
          margin: "0 auto",
        }}
        onSubmit={handleSubmit}
      >
        <Box
          component="div"
          sx={{
            margin: "0",
          }}
        >
          {registratioError && (
            <Alert severity="error">Registration failed</Alert>
          )}
          {signInError && <Alert severity="error">Sign in failed</Alert>}
        </Box>
        <TextField
          id="fullname"
          label="Full Name"
          variant="outlined"
          margin="normal"
          type="text"
          required
          value={fullname}
          onChange={(event) => setFullname(event.target.value)}
        />
        <TextField
          id="email"
          label="Email"
          variant="outlined"
          margin="normal"
          type="email"
          required
          value={email}
          onChange={(event) => setEmail(event.target.value)}
          style={{ marginTop: "0px" }}
        />
        <FormControl
          variant="outlined"
          sx={{ marginBottom: "20px", height: "56px" }}
        >
          <InputLabel htmlFor="outlined-adornment-password" required>
            Password
          </InputLabel>
          <OutlinedInput
            id="outlined-adornment-password"
            type={showPassword ? "text" : "password"}
            value={password}
            required
            onChange={(event) => setPassword(event.target.value)}
            endAdornment={
              <InputAdornment position="end">
                <IconButton
                  aria-label="toggle password visibility"
                  onClick={handleClickShowPassword}
                  onMouseDown={handleMouseDownPassword}
                  edge="end"
                >
                  {showPassword ? <VisibilityOff /> : <Visibility />}
                </IconButton>
              </InputAdornment>
            }
            label="Password"
          />
        </FormControl>
        <Button variant="contained" type="submit" sx={{ marginTop: "16px" }}>
          Register
        </Button>
      </Box>
    </div>
  );
};

export default RegisterForm;