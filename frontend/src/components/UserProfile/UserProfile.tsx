"use client";

import React, { FormEvent, useState } from "react";
import { User } from "@/lib/Types";
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
  FormHelperText,
} from "@mui/material";
import { Visibility, VisibilityOff } from "@mui/icons-material";
import axios from "axios";
import { put } from "@/lib/backendApi";

interface UserProfileProps {
  user: User;
}

function UserProfile({ user }: UserProfileProps) {
  const [fullname, setFullname] = useState(user.name);
  const [email, setEmail] = useState(user.email);
  const [oldPassword, setOldPassword] = useState("");
  const [password, setPassword] = useState("");
  const [passwordConfirmation, setPasswordConfirmation] = useState("");
  const [timer1Id, setTimer1Id] = useState<NodeJS.Timeout | undefined>(
    undefined
  );
  const [timer2Id, setTimer2Id] = useState<NodeJS.Timeout | undefined>(
    undefined
  );

  const [updateProfileError, setUpdateProfileError] = useState(false);
  const [updatePasswordError, setUpdatePasswordError] = useState(false);
  const [
    updatePasswordAuthorizationError,
    setUpdatePasswordAuthorizationError,
  ] = useState(false);

  const [updateProfileSuccess, setUpdateProfileSuccess] = useState(false);
  const [updatePasswordSuccess, setUpdatePasswordSuccess] = useState(false);

  const [showOldPassword, setShowOldPassword] = React.useState(false);
  const [showPassword, setShowPassword] = React.useState(false);
  const [showPasswordConfirmation, setShowPasswordConfirmation] =
    React.useState(false);
  const [passwordError, setPasswordError] = useState(false);
  const [passwordMatchError, setPasswordMatchError] = useState(false);

  const handleClickShowOldPassword = () => setShowOldPassword((show) => !show);

  const handleClickShowPassword = () => setShowPassword((show) => !show);

  const handleClickShowPasswordConfirmation = () =>
    setShowPasswordConfirmation((show) => !show);

  const handleMouseDownPassword = (
    event: React.MouseEvent<HTMLButtonElement>
  ) => {
    event.preventDefault();
  };

  const handleChangeOldPassword = (event: any) => {
    const newPassword = event.target.value;
    setOldPassword(newPassword);
  };

  const handleChangePassword = (event: any) => {
    const newPassword = event.target.value;
    setPassword(newPassword);
    setPasswordError(newPassword.length < 8);
    setPasswordMatchError(newPassword !== passwordConfirmation);
  };

  const handleChangePasswordConfirmation = (event: any) => {
    const newPasswordConfirmation = event.target.value;
    setPasswordConfirmation(newPasswordConfirmation);
    setPasswordMatchError(newPasswordConfirmation !== password);
  };

  const handleSubmitProfile = async (event: FormEvent) => {
    event.preventDefault();

    try {
      const res = await put("/user/update/profile", {
        name: fullname,
        email: email,
      });

      if (res) {
        if (res.status !== 200) {
          clearTimeout(timer1Id);
          setUpdateProfileError(true);
          setTimer1Id(setTimeout(() => setUpdateProfileError(false), 2000));
          return;
        }
        clearTimeout(timer1Id);
        setUpdateProfileSuccess(true);
        setTimer1Id(setTimeout(() => setUpdateProfileSuccess(false), 2000));
      }
    } catch (error: any) {
      console.error(error);
      clearTimeout(timer1Id);
      setUpdateProfileError(true);
      setTimer1Id(setTimeout(() => setUpdateProfileError(false), 2000));
      return;
    }
  };

  const handleSubmitPassword = async (event: FormEvent) => {
    event.preventDefault();

    try {
      const res = await put("/user/update/password", {
        old_password: oldPassword,
        password: password,
      });

      if (res) {
        if (res.status !== 200) {
          if (res.status === 401) {
            clearTimeout(timer2Id);
            setUpdatePasswordAuthorizationError(true);
            setTimer2Id(
              setTimeout(() => setUpdatePasswordAuthorizationError(false), 2000)
            );
            return;
          }
          clearTimeout(timer2Id);
          setUpdatePasswordError(true);
          setTimer2Id(setTimeout(() => setUpdatePasswordError(false), 2000));
          return;
        }
        clearTimeout(timer2Id);
        setUpdatePasswordSuccess(true);
        setTimer2Id(setTimeout(() => setUpdatePasswordSuccess(false), 2000));
      }
    } catch (error: any) {
      console.error(error);
      clearTimeout(timer2Id);
      setUpdatePasswordError(true);
      setTimer2Id(setTimeout(() => setUpdatePasswordError(false), 2000));
      return;
    }
  };

  return (
    <div
      style={{
        display: "flex",
        flexDirection: "column",
        alignItems: "center",
      }}
    >
      <h2
        style={{
          marginTop: "50px",
          marginBottom: "0px",
        }}
      >
        Update your profile
      </h2>
      <Box
        component="form"
        sx={{
          display: "flex",
          flexDirection: "column",
          width: "300px",
          margin: "0 auto",
        }}
        onSubmit={handleSubmitProfile}
      >
        <Box
          component="div"
          sx={{
            margin: "0",
          }}
        >
          {updateProfileError && <Alert severity="error">Update failed</Alert>}
          {updateProfileSuccess && (
            <Alert severity="success">Profile updated</Alert>
          )}
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
        <Button
          variant="contained"
          type="submit"
          sx={{ marginTop: "16px" }}
          disabled={!fullname || !email}
        >
          Update
        </Button>
      </Box>
      <h2
        style={{
          marginTop: "50px",
          marginBottom: "0px",
        }}
      >
        Change your password
      </h2>
      <Box
        component="form"
        sx={{
          display: "flex",
          flexDirection: "column",
          width: "300px",
          margin: "0 auto",
        }}
        onSubmit={handleSubmitPassword}
      >
        <Box
          component="div"
          sx={{
            margin: "0",
          }}
        ></Box>
        <FormControl
          variant="outlined"
          sx={{ marginTop: "16px", marginBottom: "8px", height: "56px" }}
        >
          <InputLabel htmlFor="outlined-adornment-password" required>
            Actual Password
          </InputLabel>
          <OutlinedInput
            id="outlined-adornment-password"
            type={showOldPassword ? "text" : "password"}
            value={oldPassword}
            required
            onChange={handleChangeOldPassword}
            endAdornment={
              <InputAdornment position="end">
                <IconButton
                  aria-label="toggle password visibility"
                  onClick={handleClickShowOldPassword}
                  onMouseDown={handleMouseDownPassword}
                  edge="end"
                >
                  {showOldPassword ? <VisibilityOff /> : <Visibility />}
                </IconButton>
              </InputAdornment>
            }
            label="Actual Password"
          />
        </FormControl>
        <FormControl
          variant="outlined"
          sx={{ marginTop: "0px", marginBottom: "8px", height: "56px" }}
        >
          <InputLabel htmlFor="outlined-adornment-password" required>
            New Password
          </InputLabel>
          <OutlinedInput
            id="outlined-adornment-password"
            type={showPassword ? "text" : "password"}
            value={password}
            required
            onChange={handleChangePassword}
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
            label="New Password"
          />
        </FormControl>
        <FormControl
          variant="outlined"
          sx={{ marginBottom: "20px", height: "56px" }}
        >
          <InputLabel htmlFor="outlined-adornment-password" required>
            Confirm Password
          </InputLabel>
          <OutlinedInput
            id="outlined-adornment-password"
            type={showPasswordConfirmation ? "text" : "password"}
            value={passwordConfirmation}
            required
            onChange={handleChangePasswordConfirmation}
            endAdornment={
              <InputAdornment position="end">
                <IconButton
                  aria-label="toggle password visibility"
                  onClick={handleClickShowPasswordConfirmation}
                  onMouseDown={handleMouseDownPassword}
                  edge="end"
                >
                  {showPasswordConfirmation ? (
                    <VisibilityOff />
                  ) : (
                    <Visibility />
                  )}
                </IconButton>
              </InputAdornment>
            }
            label="Confirm Password"
          />
          {passwordError && (
            <FormHelperText>
              Password must be at least 8 characters long
            </FormHelperText>
          )}
          {!passwordError && passwordMatchError && (
            <FormHelperText>Passwords do not match</FormHelperText>
          )}
        </FormControl>
        <Button
          variant="contained"
          type="submit"
          sx={{ marginTop: "16px", marginBottom: "16px" }}
          disabled={
            passwordMatchError ||
            passwordError ||
            !fullname ||
            !email ||
            !password
          }
        >
          Update
        </Button>
        {updatePasswordError && <Alert severity="error">Update failed</Alert>}
        {updatePasswordSuccess && (
          <Alert severity="success">Password updated</Alert>
        )}
        {updatePasswordAuthorizationError && (
          <Alert severity="error">Password incorrect</Alert>
        )}
      </Box>
    </div>
  );
}

export default UserProfile;
