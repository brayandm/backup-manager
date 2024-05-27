"use client";

import React from "react";
import { Typography, Container, Grid, Paper } from "@mui/material";

interface DashboardProps {}

function Dashboard({}: DashboardProps) {
  return (
    <div>
      <Container sx={{ paddingTop: "24px" }}>
        <Grid container spacing={3}>
          <Grid item xs={12} sm={6} md={4}>
            <Paper sx={{ p: 2 }}>
              <Typography variant="h6">Card 1</Typography>
              <Typography variant="body1">Content of Card 1</Typography>
            </Paper>
          </Grid>
          <Grid item xs={12} sm={6} md={4}>
            <Paper sx={{ p: 2 }}>
              <Typography variant="h6">Card 2</Typography>
              <Typography variant="body1">Content of Card 2</Typography>
            </Paper>
          </Grid>
          <Grid item xs={12} sm={6} md={4}>
            <Paper sx={{ p: 2 }}>
              <Typography variant="h6">Card 3</Typography>
              <Typography variant="body1">Content of Card 3</Typography>
            </Paper>
          </Grid>
        </Grid>
        <Typography
          variant="h2"
          sx={{ marginTop: "200px", textAlign: "center" }}
        >
          Welcome
        </Typography>
      </Container>
    </div>
  );
}

export default Dashboard;
